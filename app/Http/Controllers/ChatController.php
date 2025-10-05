<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\MadelineProto;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response; // Import the Response facade

class ChatController extends Controller
{
    /**
     * API endpoint to fetch recent messages and users for a user chat.
     */
    public function getUserChatData($other_user_id)
    {
        $peer = (int) $other_user_id;
        $response = ['messages' => [], 'users' => []];

        try {
            $history = MadelineProto::getClient()->messages->getHistory([
                'peer' => $peer, 'limit' => 100
            ]);
            $response['messages'] = $history['messages'] ?? [];
            $response['users'] = $history['users'] ?? [];
        } catch (\Throwable $e) {
            Log::error("ChatController: API error fetching user chat data for peer {$peer}: " . $e->getMessage());
        }
        return response()->json($response);
    }

    /**
     * API endpoint to send a message to a user.
     */
    public function sendUserMessage(Request $request, $other_user_id)
    {
        MadelineProto::getClient()->messages->sendMessage(['peer' => (int) $other_user_id, 'message' => $request->messageToSend]);
        return response()->json(['status' => 'ok']);
    }

    /**
     * API endpoint to fetch recent messages for a group chat.
     */
    public function fetchGroupsMessages($group_id)
    {
        $peer_string = "chat#{$group_id}";
        $messages = [];
        try {
            $history = MadelineProto::getClient()->messages->getHistory(['peer' => $peer_string, 'limit' => 100]);
            $messages = $history['messages'] ?? [];
        } catch (\Throwable $e) {
            Log::error("ChatController: API error fetching group messages for {$peer_string}: " . $e->getMessage());
        }
        return response()->json($messages);
    }

    /**
     * API endpoint to fetch users in a group chat.
     */
    public function fetchGroupsUsers($group_id)
    {
        $peer_string = "chat#{$group_id}";
        $users = [];
        try {
            $history = MadelineProto::getClient()->messages->getHistory(['peer' => $peer_string, 'limit' => 100]);
            $users = $history['users'] ?? [];
        } catch (\Throwable $e) {
            Log::error("ChatController: API error fetching group users for {$peer_string}: " . $e->getMessage());
        }
        return response()->json($users);
    }

    /**
     * API endpoint to send a message to a group.
     */
    public function sendGroupMessage(Request $request, $group_id)
    {
        MadelineProto::getClient()->messages->sendMessage(['peer' => "chat#{$group_id}", 'message' => $request->messageToSend]);
        return response()->json(['status' => 'ok']);
    }

    /**
     * API endpoint to fetch recent messages for a channel.
     */
    public function fetchChannelsMessages($channel_id)
    {
        $peer = (int) $channel_id;
        $messages = [];
        try {
            $history = MadelineProto::getClient()->messages->getHistory(['peer' => $peer, 'limit' => 100]);
            $messages = $history['messages'] ?? [];
        } catch (\Throwable $e) {
            Log::error("ChatController: API error fetching channel messages for peer {$peer}: " . $e->getMessage());
        }
        return response()->json($messages);
    }

    /**
     * API endpoint to send a message to a channel.
     */
    public function sendChannelMessage(Request $request, $channel_id)
    {
        $peer = (int) $channel_id;
        MadelineProto::getClient()->messages->sendMessage(['peer' => $peer, 'message' => $request->messageToSend]);
        return response()->json(['status' => 'ok']);
    }

    /**
     * API endpoint to download media from a message.
     */
    public function downloadMedia(Request $request)
    {
        $messageId = $request->query('messageId');
        $peerId = $request->query('peerId');
        $peerType = $request->query('peerType');
        if (!$messageId || !$peerId || !$peerType) {
            abort(400, 'Missing required parameters: messageId, peerId, and peerType are required.');
        }

        $peer = null;
        switch ($peerType) {
            case 'user': $peer = (int) $peerId; break;
            case 'group': $peer = "chat#{$peerId}"; break;
            case 'channel': $peer = (int) $peerId; break;
            default: abort(400, 'Invalid peerType provided.');
        }

        try {
            $mp = MadelineProto::getClient();
            $messages = $mp->channels->getMessages(['channel' => $peer, 'id' => [(int) $messageId]]);
            if (empty($messages['messages'])) {
                abort(404, 'Message not found in the specified chat.');
            }
            $message = $messages['messages'][0];
            if (!isset($message['media'])) {
                abort(404, 'No media found in this message.');
            }
            $path = storage_path('app/public/temp_media');
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
            $tempFilePath = $mp->downloadToFile($message, $path . '/' . uniqid('media_', true));
            $finalFileName = "download";
            if (isset($message['media']['document']['attributes'])) {
                foreach ($message['media']['document']['attributes'] as $attribute) {
                    if ($attribute['_'] === 'documentAttributeFilename' && isset($attribute['file_name'])) {
                        $finalFileName = $attribute['file_name'];
                        break;
                    }
                }
            }
            return response()->download($tempFilePath, $finalFileName)->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            Log::error("Media download failed for message {$messageId} in peer {$peer}: " . $e->getMessage());
            abort(500, 'Could not download media. The file may be inaccessible or expired.');
        }
    }

    /**
     * Downloads and streams a peer's profile photo.
     * This version is more robust and returns a 404 on failure instead of redirecting.
     */
    public function getProfilePhoto($peerType, $peerId)
    {
        // 1. Construct the correct peer identifier.
        $peer = null;
        switch ($peerType) {
            case 'user': $peer = (int) $peerId; break;
            case 'group': $peer = "chat#{$peerId}"; break;
            case 'channel': $peer = (int) $peerId; break;
            default:
                // If type is invalid, return a 404 Not Found response.
                return Response::make('Invalid peer type.', 404);
        }

        try {
            $mp = MadelineProto::getClient();

            // 2. Explicitly get the full peer info.
            $peerInfo = $mp->getFullInfo($peer);
            $photo = $peerInfo['full']['profile_photo'] ?? null;

            // 3. Check if a photo property exists. If not, fail gracefully.
            if (!$photo) {
                Log::info("No profile photo available for peer {$peerId} ({$peerType}).");
                return Response::make('No profile photo found.', 404);
            }

            // 4. Download the specific photo object to memory. This is more reliable.
            $file = $mp->downloadToMemory($photo);

            // 5. Stream the file from memory with appropriate headers.
            return response($file)
                ->header('Content-Type', 'image/jpeg')
                ->header('Cache-Control', 'max-age=3600, public');

        } catch (\Throwable $e) {
            // 6. If any API error occurs, log it and return a 404.
            Log::warning("Could not get profile photo for peer {$peerId} ({$peerType}): " . $e->getMessage());
            return Response::make('Error fetching profile photo.', 404);
        }
    }

    /**
     * API endpoint to fetch a paginated list of dialogs (chats).
     * Uses an offset_date and offset_id to get the next "page" of chats.
     */
    public function getDialogs(Request $request)
    {
        // Get pagination cursors from the request, with safe defaults.
        $offsetDate = $request->query('offset_date', 0);
        $offsetId = $request->query('offset_id', 0);
        $offsetPeer = $request->query('offset_peer', ['_' => 'inputPeerEmpty']);
        $limit = 50; // Fetch 50 chats per page.

        try {
            $mp = MadelineProto::getClient();

            $dialogs = $mp->messages->getDialogs([
                'offset_date' => (int) $offsetDate,
                'offset_id' => (int) $offsetId,
                'offset_peer' => $offsetPeer,
                'limit' => $limit,
            ]);

            return response()->json($dialogs);

        } catch (\Throwable $e) {
            Log::error("API error fetching paginated dialogs: " . $e->getMessage());
            return response()->json(['error' => 'Could not fetch dialogs'], 500);
        }
    }
}
