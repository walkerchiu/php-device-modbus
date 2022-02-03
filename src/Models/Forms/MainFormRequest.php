<?php

namespace WalkerChiu\DeviceModbus\Models\Forms;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use WalkerChiu\Core\Models\Forms\FormRequest;

class MainFormRequest extends FormRequest
{
    /**
     * @Override Illuminate\Foundation\Http\FormRequest::getValidatorInstance
     */
    protected function getValidatorInstance()
    {
        $request = Request::instance();
        $data = $this->all();
        if (
            $request->isMethod('put')
            && empty($data['id'])
            && isset($request->id)
        ) {
            $data['id'] = (string) $request->id;
            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return Array
     */
    public function attributes()
    {
        return [
            'channel_id'  => trans('php-device-modbus::main.channel_id'),
            'serial'      => trans('php-device-modbus::main.serial'),
            'identifier'  => trans('php-device-modbus::main.identifier'),
            'order'       => trans('php-device-modbus::main.order'),
            'slave_id'    => trans('php-device-modbus::main.slave_id'),
            'is_enabled'  => trans('php-device-modbus::main.is_enabled'),

            'name'        => trans('php-device-modbus::main.name'),
            'description' => trans('php-device-modbus::main.description'),
            'location'    => trans('php-device-modbus::main.location')
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return Array
     */
    public function rules()
    {
        $rules = [
            'channel_id'  => ['required','string','exists:'.config('wk-core.table.device-modbus.channels').',id'],
            'serial'      => '',
            'identifier'  => 'required|string|max:255',
            'order'       => 'nullable|numeric|min:0',
            'slave_id'    => 'required|integer|between:1,255',
            'is_enabled'  => 'boolean',

            'name'        => 'required|string|max:255',
            'description' => '',
            'location'    => ''
        ];

        $request = Request::instance();
        if (
            $request->isMethod('put')
            && isset($request->id)
        ) {
            $rules = array_merge($rules, ['id' => ['required','string','exists:'.config('wk-core.table.device-modbus.main').',id']]);
        } elseif ($request->isMethod('post')) {
            $rules = array_merge($rules, ['id' => ['nullable','string','exists:'.config('wk-core.table.device-modbus.main').',id']]);
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return Array
     */
    public function messages()
    {
        return [
            'id.string'           => trans('php-core::validation.string'),
            'id.exists'           => trans('php-core::validation.exists'),
            'channel_id.required' => trans('php-core::validation.required'),
            'channel_id.string'   => trans('php-core::validation.string'),
            'channel_id.exists'   => trans('php-core::validation.exists'),
            'identifier.required' => trans('php-core::validation.required'),
            'identifier.string'   => trans('php-core::validation.string'),
            'identifier.max'      => trans('php-core::validation.max'),
            'order.numeric'       => trans('php-core::validation.numeric'),
            'order.min'           => trans('php-core::validation.min'),
            'slave_id.required'   => trans('php-core::validation.required'),
            'slave_id.integer'    => trans('php-core::validation.integer'),
            'slave_id.between'    => trans('php-core::validation.between'),
            'is_enabled.required' => trans('php-core::validation.required'),
            'is_enabled.boolean'  => trans('php-core::validation.boolean'),

            'name.required'       => trans('php-core::validation.required'),
            'name.string'         => trans('php-core::validation.string'),
            'name.max'            => trans('php-core::validation.max')
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after( function ($validator) {
            $data = $validator->getData();
            if (isset($data['identifier'])) {
                $result = config('wk-core.class.device-modbus.main')::where('identifier', $data['identifier'])
                                ->when(isset($data['channel_id']), function ($query) use ($data) {
                                    return $query->where('channel_id', $data['channel_id']);
                                  })
                                ->when(isset($data['id']), function ($query) use ($data) {
                                    return $query->where('id', '<>', $data['id']);
                                  })
                                ->exists();
                if ($result)
                    $validator->errors()->add('identifier', trans('php-core::validation.unique', ['attribute' => trans('php-device-modbus::main.identifier')]));
            }
        });
    }
}
