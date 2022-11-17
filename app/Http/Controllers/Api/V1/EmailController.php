<?php

namespace App\Http\Controllers\Api\V1;

use App\Enum\EmailStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreEmailRequest;
use App\Http\Requests\Api\V1\UpdateEmailRequest;
use App\Models\Email;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

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
    public function index()
    {
        $emails = Email::where('user_id', Auth::id())->paginate(10);

        $resp = [
            'msg'   => Lang::get('response.paginated_list_of_emails'),
            'data'  => [
                'emails' => $emails
            ]
        ];
        return response()->json($resp, 200);
    }

    /**
     * Create Email
     * @authenticated
     *
     * @responseFile 200 doc/api/v1/email/store.success.json
     * @responseFile 401 doc/api/default/401.json
     * @responseFile 422 doc/api/default/422.json
     */
    public function store(StoreEmailRequest $request)
    {
        $email = Email::create([
            'subject'   => $request->subject,
            'to'        => $request->to,
            'message'   => $request->message,
            'status'    => EmailStatusEnum::PENDING,
            'user_id'   => Auth::id(),
        ]);

        $resp = [
            'msg'   => Lang::get('response.record_created_successfully'),
            'data'  => [
                'email' => $email
            ]
        ];
        return response()->json($resp, 201);
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
    public function show(Email $email, $status = null)
    {
        if ($status && $status !== $email->status) {
            throw new ModelNotFoundException();
        }

        $resp = [
            'msg'   => Lang::get('response.email_detail'),
            'data'  => [
                'email' => $email
            ]
        ];
        return response()->json($resp, 200);
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
    public function update(UpdateEmailRequest $request, Email $email)
    {
        $email->subject = $request->subject;
        $email->to      = $request->to;
        $email->message = $request->message;

        $email->update();

        $resp = [
            'msg'   => Lang::get('response.it_has_been_updated_successfully'),
            'data'  => [
                'email' => $email
            ]
        ];
        return response()->json($resp, 200);
    }
}
