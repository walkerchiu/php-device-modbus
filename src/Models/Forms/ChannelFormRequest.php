<?php

namespace WalkerChiu\DeviceModbus\Models\Forms;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use WalkerChiu\Core\Models\Forms\FormRequest;

class ChannelFormRequest extends FormRequest
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
            'serial'          => trans('php-device-modbus::channel.serial'),
            'identifier'      => trans('php-device-modbus::channel.identifier'),
            'order'           => trans('php-device-modbus::channel.order'),
            'is_enabled'      => trans('php-device-modbus::channel.is_enabled'),

            'protocol'        => trans('php-device-modbus::channel.protocol'),
            'interface'       => trans('php-device-modbus::channel.interface'),

            'baudrate'        => trans('php-device-modbus::channel.baudrate'),
            'parity'          => trans('php-device-modbus::channel.parity'),
            'stop_bit'        => trans('php-device-modbus::channel.stop_bit'),

            'ip'              => trans('php-device-modbus::channel.ip'),
            'port'            => trans('php-device-modbus::channel.port'),

            'scan_interval'   => trans('php-device-modbus::channel.scan_interval'),
            'polling_timeout' => trans('php-device-modbus::channel.polling_timeout'),
            'retry_interval'  => trans('php-device-modbus::channel.retry_interval'),

            'name'            => trans('php-device-modbus::channel.name'),
            'description'     => trans('php-device-modbus::channel.description')
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
            'serial'      => '',
            'identifier'  => 'required|string|max:255',
            'order'       => 'nullable|numeric|min:0',
            'is_enabled'  => 'boolean',

            'protocol'        => ['required', Rule::in(config('wk-core.class.device-modbus.protocolType')::getCodes())],
            'interface'       => 'required|string|min:2',
            'scan_interval'   => 'nullable|integer|between:1,65535',
            'polling_timeout' => 'nullable|integer|between:1,10000',
            'retry_interval'  => 'nullable|integer',


            'name'        => 'required|string|max:255',
            'description' => ''
        ];

        $request = Request::instance();
        if (
            $request->isMethod('put')
            && isset($request->id)
        ) {
            $rules = array_merge($rules, ['id' => ['required','string','exists:'.config('wk-core.table.device-modbus.channels').',id']]);
        } elseif ($request->isMethod('post')) {
            $rules = array_merge($rules, ['id' => ['nullable','string','exists:'.config('wk-core.table.device-modbus.channels').',id']]);
        }

        switch ($request->protocol) {
            case "ASCII":
            case "RTU":
                $rules = array_merge($rules, [
                    'baudrate' => ['nullable', Rule::in(config('wk-core.class.core.baud')::getCodes())],
                    'parity'   => ['nullable', Rule::in(config('wk-core.class.core.parity')::getCodes())],
                    'stop_bit' => ['nullable', Rule::in(config('wk-core.class.core.stopbit')::getCodes())]
                ]);
                break;
            case "TCP":
                $rules = array_merge($rules, [
                    "ip"   => 'required|ip',
                    'port' => 'required|integer|between:1,65535'
                ]);
                break;
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
        $messages = [
            'id.string'               => trans('php-core::validation.string'),
            'id.exists'               => trans('php-core::validation.exists'),
            'identifier.required'     => trans('php-core::validation.required'),
            'identifier.string'       => trans('php-core::validation.required'),
            'identifier.max'          => trans('php-core::validation.max'),
            'order.numeric'           => trans('php-core::validation.numeric'),
            'order.min'               => trans('php-core::validation.min'),
            'is_enabled.required'     => trans('php-core::validation.required'),
            'is_enabled.boolean'      => trans('php-core::validation.boolean'),

            'protocol.required'       => trans('php-core::validation.required'),
            'protocol.in'             => trans('php-core::validation.in'),
            'interface.required'      => trans('php-core::validation.required'),
            'interface.string'        => trans('php-core::validation.string'),
            'interface.min'           => trans('php-core::validation.min'),
            'scan_interval.integer'   => trans('php-core::validation.integer'),
            'scan_interval.between'   => trans('php-core::validation.between'),
            'polling_timeout.integer' => trans('php-core::validation.integer'),
            'polling_timeout.between' => trans('php-core::validation.between'),
            'retry_interval.integer'  => trans('php-core::validation.integer'),

            'name.required'           => trans('php-core::validation.required'),
            'name.string'             => trans('php-core::validation.string'),
            'name.max'                => trans('php-core::validation.max')
        ];

        $request = Request::instance();
        switch ($request->protocol) {
            case "ASCII":
            case "RTU":
                $messages = array_merge($messages, [
                    'baudrate.in' => trans('php-core::validation.in'),
                    'parity.in'   => trans('php-core::validation.in'),
                    'stop_bit.in' => trans('php-core::validation.in')
                ]);
                break;
            case "TCP":
                $messages = array_merge($messages, [
                    'ip.required'   => trans('php-core::validation.required'),
                    'ip.ip'         => trans('php-core::validation.ip'),
                    'port.required' => trans('php-core::validation.required'),
                    'port.integer'  => trans('php-core::validation.integer'),
                    'port.between'  => trans('php-core::validation.between')
                ]);
                break;
        }

        return $messages;
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
                $result = config('wk-core.class.device-modbus.channel')::where('identifier', $data['identifier'])
                                ->when(isset($data['id']), function ($query) use ($data) {
                                    return $query->where('id', '<>', $data['id']);
                                  })
                                ->exists();
                if ($result)
                    $validator->errors()->add('identifier', trans('php-core::validation.unique', ['attribute' => trans('php-device-modbus::channel.identifier')]));
            }
        });
    }
}
