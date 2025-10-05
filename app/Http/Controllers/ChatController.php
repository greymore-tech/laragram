<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\MadelineProto;
use Illuminate\Support\Facades\Log;

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
     * API endpoint to download media from a message. This is the corrected version.
     */
    public function downloadMedia(Request $request)
    {
        // 1. Validate all required parameters.
        $messageId = $request->query('messageId');
        $peerId = $request->query('peerId');
        $peerType = $request->query('peerType');

        if (!$messageId || !$peerId || !$peerType) {
            abort(400, 'Missing required parameters: messageId, peerId, and peerType are required.');
        }

        // 2. Construct the correct peer identifier.
        $peer = null;
        switch ($peerType) {
            case 'user':
                $peer = (int) $peerId;
                break;
            case 'group':
                $peer = "chat#{$peerId}";
                break;
            case 'channel':
                $peer = (int) $peerId;
                break;
            default:
                abort(400, 'Invalid peerType provided.');
        }

        try {
            $mp = MadelineProto::getClient();

            // 3. THE FIX: Fetch the message using BOTH the peer and the message ID.
            //    The 'channels->getMessages' method is a robust way to do this for all peer types.
            $messages = $mp->channels->getMessages(['channel' => $peer, 'id' => [(int) $messageId]]);

            if (empty($messages['messages'])) {
                abort(404, 'Message not found in the specified chat.');
            }
            $message = $messages['messages'][0];

            if (!isset($message['media'])) {
                abort(404, 'No media found in this message.');
            }

            // 4. Prepare the temporary download path.
            $path = storage_path('app/public/temp_media');
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }

            // 5. Download the file from Telegram.
            $tempFilePath = $mp->downloadToFile($message, $path . '/' . uniqid('media_', true));

            // 6. Determine a user-friendly filename.
            $finalFileName = "download"; // Default filename.
            if (isset($message['media']['document']['attributes'])) {
                foreach ($message['media']['document']['attributes'] as $attribute) {
                    if ($attribute['_'] === 'documentAttributeFilename' && isset($attribute['file_name'])) {
                        $finalFileName = $attribute['file_name'];
                        break;
                    }
                }
            }

            // 7. Stream the download to the user and delete the temporary file afterwards.
            return response()->download($tempFilePath, $finalFileName)->deleteFileAfterSend(true);

        } catch (\Throwable $e) {
            Log::error("Media download failed for message {$messageId} in peer {$peer}: " . $e->getMessage());
            abort(500, 'Could not download media. The file may be inaccessible or expired.');
        }
    }

    /**
     * Downloads and streams a peer's profile photo.
     * This acts as a secure proxy for displaying images in the frontend.
     * It now correctly handles different peer types (user, group, channel).
     *
     * @param string $peerType The type of peer ('user', 'group', 'channel').
     * @param string $peerId   The ID of the peer.
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function getProfilePhoto($peerType, $peerId)
    {
        // 1. Construct the correct peer identifier based on the provided type.
        $peer = null;
        switch ($peerType) {
            case 'user':
                $peer = (int) $peerId;
                break;
            case 'group':
                $peer = "chat#{$peerId}";
                break;
            case 'channel':
                $peer = (int) $peerId;
                break;
            default:
                // If the type is invalid, immediately redirect to the placeholder.
                return redirect('/img/placeholder.png');
        }

        try {
            $mp = MadelineProto::getClient();

            // 2. Download the photo to memory using the correct peer identifier.
            $file = $mp->downloadToMemory($peer);

            // 3. Stream the file from memory with appropriate headers.
            // Caching for 1 hour to reduce API calls on the same photo.
            return response($file)
                ->header('Content-Type', 'image/jpeg')
                ->header('Cache-Control', 'max-age=3600, public');

        } catch (\Throwable $e) {
            // 4. If a photo doesn't exist or any other error occurs,
            //    log the warning and redirect to the placeholder image.
            Log::warning("Could not get profile photo for peer {$peerId} ({$peerType}): " . $e->getMessage());
            return redirect('/img/placeholder.png');
        }
    }
}
