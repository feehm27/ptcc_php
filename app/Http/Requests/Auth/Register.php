<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Validation\Validator;

//Utils
use App\Http\Utils\StatusCodeUtils;
use Illuminate\Support\Facades\Auth;

class Register extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name' 		  		=> 'required|string|max:255',
			'email' 	  		=> 'required|string|email|max:255|unique:users',
			'is_client'   		=> 'required|boolean',
			'is_advocate' 		=> 'required|boolean',
			'facebook_id' 	    => 'nullable|string',
			'password' 	 		=> 'required|string|min:8',
			'advocate_user_id'  => 'nullable|integer',
		];
	}

	/**
	 * Get the error messages for thec defined validation rules.
	 *
	 * @return array
	 */
	public function messages()
	{
		return [
			'email.unique' => 'Já existe um usuário cadastrado com este email',
		];
	}

	/**
	 * Prepare the data for validation.
	 *
	 * @return void
	 */
	protected function prepareForValidation()
	{
		$user = Auth::user();
		$this->advocateUserId = null;

		if($user && $user->is_advocate === 1){
			$this->advocateUserId = $user->id;
		}

		$this->merge([
			'advocate_user_id'  => $this->advocateUserId,
			'password' 			=>  Hash::make($this->password),
		]);
	}

	/**
	 * Return validation errors as json response
	 *
	 * @param Validator $validator
	 */
	protected function failedValidation(Validator $validator)
	{
		$response = [
			'status_code' => StatusCodeUtils::BAD_REQUEST,
			'errors'      => $validator->errors(),
		];
		throw new HttpResponseException(response()->json($response, StatusCodeUtils::BAD_REQUEST));
	}
}
