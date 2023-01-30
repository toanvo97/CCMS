<?php

return [
    'custom_fields'		        => 'カスタムフィールド',
    'manage'                    => 'Manage',
    'field'		                => 'フィールド',
    'about_fieldsets_title'		=> 'フィールドセットについて',
    'about_fieldsets_text'		=> 'Fieldsets allow you to create groups of custom fields that are frequently re-used for specific asset model types.',
    'custom_format'             => 'Custom Regex format...',
    'encrypt_field'      	        => 'このフィールドの値をデータベースにて暗号化する',
    'encrypt_field_help'      => '警告: 暗号化フィールドは検索することができなくなります。',
    'encrypted'      	        => '暗号化',
    'fieldset'      	        => 'フィールドセット',
    'qty_fields'      	      => '数量フィールド',
    'fieldsets'      	        => 'フィールドセット',
    'fieldset_name'           => 'フィールドセット名',
    'field_name'              => 'フィールド名',
    'field_values'            => 'フィールド値',
    'field_values_help'       => '選択可能なオプションを1行に1つ追加します。最初の行以外の空白行は無視されます。',
    'field_element'           => 'フォームエレメント',
    'field_element_short'     => 'エレメント',
    'field_format'            => 'フォーマット',
    'field_custom_format'     => 'カスタム形式：',
    'field_custom_format_help'     => 'このフィールドでは、検証のために正規表現を使用できます。たとえば、カスタムフィールド値に有効なIMEI（15桁）が含まれていることを検証するには、<code>regex：/ ^ [0-9]{15} $ / </code>を使用します。',
    'required'   		          => '必須',
    'req'   		              => '必須',
    'used_by_models'   		    => '型番で使用',
    'order'   		            => '順番',
    'create_fieldset'         => '新しいフィールドセット',
    'create_fieldset_title' => 'Create a new fieldset',
    'create_field'            => '新しいユーザー設定フィールド',
    'create_field_title' => 'Create a new custom field',
    'value_encrypted'      	        => 'このフィールドの値は、データベースで暗号化されます。管理者ユーザーのみが復号化された値を表示することができます。',
    'show_in_email'     => 'このフィールドの値を、ユーザーに送信されるチェックアウト メールに含めますか？（暗号化されたフィールドの値はメールに含めることはできません。）',
    'help_text' => 'Help Text',
    'help_text_description' => 'This is optional text that will appear below the form elements while editing an asset to provide context on the field.',
    'about_custom_fields_title' => 'About Custom Fields',
    'about_custom_fields_text' => 'Custom fields allow you to add arbitrary attributes to assets.',
    'add_field_to_fieldset' => 'Add Field to Fieldset',
    'make_optional' => 'Required - click to make optional',
    'make_required' => 'Optional - click to make required',
    'reorder' => 'Reorder',
    'db_field' => 'DB Field',
    'db_convert_warning' => 'WARNING. This field is in the custom fields table as <code> :db_column </code> but should be :expected </code>.'
];