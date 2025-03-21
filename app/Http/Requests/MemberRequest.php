<?php

namespace App\Http\Requests;

use App\Models\Member;
use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
{
    public function member()
    {
        $uuid = $this->headers->get('uuid', 'test');
        $member = Member::where('uuid', $uuid)->first();
        if (!$member) {
            $model = $this->headers->get('model', 'jd-11');
            $os = $this->headers->get('os', '9');
            $member = new Member();
            $member->uuid = $uuid;
            $member->model = $model;
            $member->os = $os;
            $member->save();
        }
        return $member;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
