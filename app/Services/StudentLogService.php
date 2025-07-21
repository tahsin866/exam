<?php

namespace App\Services;

use App\Models\reg_stu_information;
use App\Models\reg_stu_information_log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StudentLogService
{
    /**
     * Create a new log entry for student information.
     *
     * @param reg_stu_information $studentInfo
     * @param Model $actor Admin or User model
     * @param string $status
     * @param string|null $message
     * @param UploadedFile|null $feedbackImage
     * @return negran_mumtahin_log
     */
    public function createLog(
        reg_stu_information $studentInfo,
        Model $actor,
        string $status,
        ?string $message = null,
        ?UploadedFile $feedbackImage = null
    ): reg_stu_information_log {
        $data = [
            'reg_student_id' => $studentInfo->id,
            'status' => $status,
            'message' => $message,
        ];

        if ($feedbackImage) {
            $path = $feedbackImage->store('feedback_images', 'public');
            $data['feedback_image'] = $path;
        }

        // Create the log entry
        $log = new reg_stu_information_log($data);

        // Associate with the actor (polymorphic relationship)
        $actor->studentLogs()->save($log);

        return $log;
    }
}
