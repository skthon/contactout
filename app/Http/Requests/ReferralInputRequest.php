<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Referral;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReferralInputRequest extends FormRequest
{
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(["status" => "error", "message" => $validator->errors()->first()])
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'emails'   => 'required|array|min:1|max:5',
            'emails.*' => 'required|email|distinct|max:64',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // Inbuilt messages should be enough here
        ];
    }

    /**
    * @param  \Illuminate\Validation\Validator  $validator
    * @return void
    */
    public function withValidator(Validator $validator)
    {
        $emails = collect($validator->getData())->get('emails') ?? '';
        $validator->after(function ($validator) use ($emails) {
            $existingUserEmails = User::select(['email'])
                ->whereIn('email', $emails)
                ->pluck('email')
                ->all();
            $alreadyRegisteredEmails = collect($existingUserEmails)->intersect($emails);

            $existingReferredEmails = Referral::select(['referred_email'])
                ->whereIn('referred_email', $emails)
                ->pluck('referred_email')
                ->all();
            $alreadyReferredEmails = collect($existingReferredEmails)->intersect($emails);

            $duplicateEmails = collect($alreadyReferredEmails)->merge($alreadyRegisteredEmails)->unique();
            if ($duplicateEmails->isNotEmpty()) {
                $validator->errors()->add(
                    'emails',
                     implode(", ", $duplicateEmails->all()) . ' is/are either already registered or already invited'
                );
            }
        });
    }
}
