<?php

namespace WalkerChiu\DeviceModbus\Models\Forms;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use WalkerChiu\Core\Models\Forms\FormRequest;

class SettingFormRequest extends FormRequest
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
            'main_id'     => trans('php-device-modbus::setting.main_id'),
            'serial'      => trans('php-device-modbus::setting.serial'),
            'identifier'  => trans('php-device-modbus::setting.identifier'),
            'order'       => trans('php-device-modbus::setting.order'),
            'is_enabled'  => trans('php-device-modbus::setting.is_enabled'),

            'typology'           => trans('php-device-modbus::setting.typology'),
            'function_code'      => trans('php-device-modbus::setting.function_code'),
            'format'             => trans('php-device-modbus::setting.format'),
            'data_start_address' => trans('php-device-modbus::setting.data_start_address'),
            'data_count'         => trans('php-device-modbus::setting.data_count'),
            'scale_ratio'        => trans('php-device-modbus::setting.scale_ratio'),

            'name'        => trans('php-device-modbus::setting.name'),
            'description' => trans('php-device-modbus::setting.description'),
            'location'    => trans('php-device-modbus::setting.location')
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
            'main_id'     => ['required','string','exists:'.config('wk-core.table.device-modbus.main').',id'],
            'serial'      => '',
            'identifier'  => 'required|string|max:255',
            'order'       => 'nullable|numeric|min:0',
            'is_enabled'  => 'boolean',

            'typology'           => ['required', Rule::in(config('wk-core.class.device-modbus.typology')::getCodes())],
            'function_code'      => ['required', Rule::in(config('wk-core.class.device-modbus.functionCode')::getCodes())],
            'format'             => ['required', Rule::in(config('wk-core.class.device-modbus.format')::getCodes())],
            'data_start_address' => 'required|integer|min:0',
            'data_count'         => 'required|integer|min:1',
            'scale_ratio'        => 'nullable|numeric|not_in:0|between:-100000,100000',

            'name'        => 'required|string|max:255',
            'description' => '',
            'location'    => ''
        ];

        $request = Request::instance();
        if (
            $request->isMethod('put')
            && isset($request->id)
        ) {
            $rules = array_merge($rules, ['id' => ['required','string','exists:'.config('wk-core.table.device-modbus.settings').',id']]);
        } elseif ($request->isMethod('post')) {
            $rules = array_merge($rules, ['id' => ['nullable','string','exists:'.config('wk-core.table.device-modbus.settings').',id']]);
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
            'main_id.required'    => trans('php-core::validation.required'),
            'main_id.string'      => trans('php-core::validation.string'),
            'main_id.exists'      => trans('php-core::validation.exists'),
            'identifier.required' => trans('php-core::validation.required'),
            'identifier.string'   => trans('php-core::validation.string'),
            'identifier.max'      => trans('php-core::validation.max'),
            'order.numeric'       => trans('php-core::validation.numeric'),
            'order.min'           => trans('php-core::validation.min'),
            'is_enabled.required' => trans('php-core::validation.required'),
            'is_enabled.boolean'  => trans('php-core::validation.boolean'),

            'typology.required'           => trans('php-core::validation.required'),
            'typology.in'                 => trans('php-core::validation.in'),
            'function_code.required'      => trans('php-core::validation.required'),
            'function_code.in'            => trans('php-core::validation.in'),
            'format.required'             => trans('php-core::validation.required'),
            'format.in'                   => trans('php-core::validation.in'),
            'data_start_address.required' => trans('php-core::validation.required'),
            'data_start_address.integer'  => trans('php-core::validation.integer'),
            'data_start_address.min'      => trans('php-core::validation.min'),
            'data_count.required'         => trans('php-core::validation.required'),
            'data_count.integer'          => trans('php-core::validation.integer'),
            'data_count.min'              => trans('php-core::validation.min'),
            'scale_ratio.numeric'         => trans('php-core::validation.numeric'),
            'scale_ratio.not_in'          => trans('php-core::validation.not_in'),
            'scale_ratio.between'         => trans('php-core::validation.between'),

            'name.required' => trans('php-core::validation.required'),
            'name.string'   => trans('php-core::validation.string'),
            'name.max'      => trans('php-core::validation.max')
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
                $result = config('wk-core.class.device-modbus.setting')::where('identifier', $data['identifier'])
                                ->when(isset($data['main_id']), function ($query) use ($data) {
                                    return $query->where('main_id', $data['main_id']);
                                  })
                                ->when(isset($data['id']), function ($query) use ($data) {
                                    return $query->where('id', '<>', $data['id']);
                                  })
                                ->exists();
                if ($result)
                    $validator->errors()->add('identifier', trans('php-core::validation.unique', ['attribute' => trans('php-device-modbus::setting.identifier')]));
            }
        });
    }
}
