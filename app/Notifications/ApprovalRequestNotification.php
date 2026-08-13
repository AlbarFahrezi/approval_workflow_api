<?php

namespace App\Notifications;

use App\Models\ApprovalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApprovalRequestNotification extends Notification
{
    use Queueable;

    public function __construct(
        public ApprovalRequest $approvalRequest,
        public string $message
    ) {
    }

    /**
     * Notification disimpan ke database.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Data yang disimpan ke tabel notifications.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'approval_request_id' => $this->approvalRequest->id,
            'title' => $this->approvalRequest->title,
            'message' => $this->message,
            'status' => $this->approvalRequest->status,
        ];
    }
}