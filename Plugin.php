<?php namespace Pathao\Mailer;

use System\Classes\PluginBase;
use System\Models\MailSettings;

class Plugin extends PluginBase
{
    const MODE_SENDGRID = 'sendgrid';

    /**
     * @var boolean Determine if this plugin should have elevated privileges.
     */
    public $elevated = true;

    public function pluginDetails()
    {
        return [
            'name'        => 'SendGrid Mailer Driver',
            'description' => 'This plugin is used in October CMS for email sending functionality through Sendgrid driver.',
            'author'      => 'Pathao',
            'icon'        => 'icon-user'
        ];
    }

    public function boot()
    {
        \Event::listen('backend.form.extendFields', function($widget) {

            // Only for the Settings controller
            if (!$widget->getController() instanceof \System\Controllers\Settings) {
                return;
            }

            // Only for the MailSettings model
            if (!$widget->model instanceof MailSettings) {
                return;
            }

            $field = $widget->getField('send_mode');
            $field->options(array_merge($field->options(), [self::MODE_SENDGRID => trans('pathao.mailer::lang.mail.sendgrid')]));

            // Add an extra birthday field
            $widget->addFields([
                'sendgrid_api_key' => [
                    'label' => trans('pathao.mailer::lang.mail.sendgrid_api_key'),
                    'comment' => trans('pathao.mailer::lang.mail.sendgrid_api_key_comment'),
                    'tab' => ' ',
                    'trigger' => [
                        'action' => 'show',
                        'field' => 'send_mode',
                        'condition' => 'value[sendgrid]'
                    ]
                ],
            ], 'secondary');
        });
    }
}
