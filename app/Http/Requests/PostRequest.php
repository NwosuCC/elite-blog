<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Authorization already implemented in \App\Policies\PostPolicy::class

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $post = $this->route('post');

        return [
            'category' => [
                'required',
                Rule::exists('categories', 'id')->whereNull('deleted_at')
                /*Rule::exists('categories', 'id')->where(function ($query){
                    $query->whereNull('deleted_at');
                })*/
            ],
            'title' => [
                'required', 'min:3', 'max:30',
                Rule::unique('posts')->ignore($post ? $post->id : ''),
            ],
            'body'  => ['required', 'min:3'],
            'publish_at' => ['nullable', 'date', 'after_or_equal:now'],
        ];
    }
}
