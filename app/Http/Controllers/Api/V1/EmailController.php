<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreEmailRequest;
use App\Http\Requests\Api\V1\UpdateEmailRequest;
use App\Models\Email;
use App\Services\ApiResponse;
use App\Services\EmailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @group  Emails
 *
 * APIs for managing emails
 */
class EmailController extends Controller
{
    /**
     * Paginated list of emails
     * @authenticated
     *
     * @responseFile 200 doc/api/v1/email/emails.success.json
     * @responseFile 401 doc/api/default/401.json
     */
    public function index(): JsonResponse
    {
        $emails = Email::where('user_id', Auth::id())->paginate(10);

        return ApiResponse::fullList(compact('emails'));
    }

    /**
     * Create Email
     * @authenticated
     *
     * @responseFile 200 doc/api/v1/email/store.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 422 doc/api/default/422.json
     */
    public function store(StoreEmailRequest $request, EmailService $emailService): JsonResponse
    {
        $email = $emailService->store($request->validated());

        return ApiResponse::created(compact('email'));
    }

    /**
     * Email by id
     * @authenticated
     *
     * @urlParam email integer required The ID of the email.
     * @urlParam status The status. Example: PENDING
     *
     * @responseFile 200 doc/api/v1/email/show.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 404 doc/api/default/404.json
    */
    public function show(EmailService $emailService, $emailId, $status = null): JsonResponse
    {
        $email = $emailService->getByIdAndStatus($emailId, $status);

        return ApiResponse::detail(compact('email'));
    }

    /**
     * Update Email
     * @authenticated
     *
     * @urlParam email integer required The ID of the email.
     *
     * @responseFile 200 doc/api/v1/email/update.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 404 doc/api/default/404.json
     * @responseFile 422 doc/api/default/422.json
     */
    public function update(UpdateEmailRequest $request, EmailService $emailService, $emailId): JsonResponse
    {
        $email = $emailService->update($emailId, $request->validated());

        return ApiResponse::updated(compact('email'));
    }
}
