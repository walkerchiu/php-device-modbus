<?php

namespace WalkerChiu\DeviceModbus\Models\Forms;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use WalkerChiu\Core\Models\Forms\FormRequest;

class SettingStateFormRequest extends FormRequest
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
            'setting_id'  => trans('php-device-modbus::settingState.setting_id'),
            'serial'      => trans('php-device-modbus::settingState.serial'),
            'identifier'  => trans('php-device-modbus::settingState.identifier'),
            'mean'        => trans('php-device-modbus::settingState.mean'),
            'order'       => trans('php-device-modbus::settingState.order'),
            'is_enabled'  => trans('php-device-modbus::settingState.is_enabled'),

            'name'        => trans('php-device-modbus::settingState.name'),
            'description' => trans('php-device-modbus::settingState.description')
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
            'setting_id'     => ['required','string','exists:'.config('wk-core.table.device-modbus.settings').',id'],
            'serial'      => '',
            'identifier'  => 'required|string|max:255',
            'mean'        => 'required|string',
            'order'       => 'nullable|numeric|min:0',
            'is_enabled'  => 'boolean',

            'name'        => 'required|string|max:255',
            'description' => ''
        ];

        $request = Request::instance();
        if (
            $request->isMethod('put')
            && isset($request->id)
        ) {
            $rules = array_merge($rules, ['id' => ['required','string','exists:'.config('wk-core.table.device-modbus.main_states').',id']]);
        } elseif ($request->isMethod('post')) {
            $rules = array_merge($rules, ['id' => ['nullable','string','exists:'.config('wk-core.table.device-modbus.main_states').',id']]);
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
            'setting_id.required' => trans('php-core::validation.required'),
            'setting_id.string'   => trans('php-core::validation.string'),
            'setting_id.exists'   => trans('php-core::validation.exists'),
            'identifier.required' => trans('php-core::validation.required'),
            'identifier.string'   => trans('php-core::validation.string'),
            'identifier.max'      => trans('php-core::validation.max'),
            'mean.required'       => trans('php-core::validation.required'),
            'mean.string'         => trans('php-core::validation.string'),
            'order.numeric'       => trans('php-core::validation.numeric'),
            'order.min'           => trans('php-core::validation.min'),
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
                $result = config('wk-core.class.device-modbus.settingState')::where('identifier', $data['identifier'])
                                ->when(isset($data['setting_id']), function ($query) use ($data) {
                                    return $query->where('setting_id', $data['setting_id']);
                                  })
                                ->when(isset($data['id']), function ($query) use ($data) {
                                    return $query->where('id', '<>', $data['id']);
                                  })
                                ->exists();
                if ($result)
                    $validator->errors()->add('identifier', trans('php-core::validation.unique', ['attribute' => trans('php-device-modbus::settingState.identifier')]));
            }
        });
    }
}
