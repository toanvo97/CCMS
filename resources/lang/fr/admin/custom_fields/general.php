<?php

return [
    'custom_fields'		        => 'Champs personnalisés',
    'manage'                    => 'Gérer',
    'field'		                => 'Champ',
    'about_fieldsets_title'		=> 'A propos des fieldsets',
    'about_fieldsets_text'		=> 'Les jeux de champs permettent de grouper les champs supplémentaires affectés à des modèles d\'actifs.',
    'custom_format'             => 'Format Regex personnalisé...',
    'encrypt_field'      	        => 'Chiffrer la valeur de ce champ dans la base de données',
    'encrypt_field_help'      => 'AVERTISSEMENT: Chiffrer un champ en rend la recherche sur le contenu impossible.',
    'encrypted'      	        => 'Chiffré',
    'fieldset'      	        => 'Fieldset',
    'qty_fields'      	      => 'Qté de champs',
    'fieldsets'      	        => 'Fieldsets',
    'fieldset_name'           => 'Nom du fieldset',
    'field_name'              => 'Nom du champ',
    'field_values'            => 'Valeurs de champ',
    'field_values_help'       => 'Ajouter des options sélectionnables, une par ligne. Les lignes vides autres que la première ligne seront ignorées.',
    'field_element'           => 'Élément de formulaire',
    'field_element_short'     => 'Elément',
    'field_format'            => 'Format',
    'field_custom_format'     => 'Format personnalisé',
    'field_custom_format_help'     => 'Ce champ vous permet d\'utiliser une expression regex pour la validation. Il devrait commencer par "regex:" - par exemple, pour valider qu\'une valeur de champ personnalisée contient un IMEI valide (15 chiffres numériques), vous utiliseriez <code>regex: / ^[0-9]{15}$ /</code>.',
    'required'   		          => 'Requis',
    'req'   		              => 'Req.',
    'used_by_models'   		    => 'Utilisé par les modèles',
    'order'   		            => 'Commande',
    'create_fieldset'         => 'Nouveau Fieldset',
    'create_fieldset_title' => 'Créer un nouveau jeu de champs',
    'create_field'            => 'Nouveau champ personnalisé',
    'create_field_title' => 'Créer un champ personnalisé',
    'value_encrypted'      	        => 'La valeur de ce champ est chiffrée dans la base de donnée. Seuls les administrateurs seront capable de voir les données déchiffrées',
    'show_in_email'     => 'Inclure la valeur de ce champ dans les e-mails envoyés à l\'utilisateur? Les champs cryptés ne peuvent pas être inclus dans les e-mails.',
    'help_text' => 'Texte d\'aide',
    'help_text_description' => 'This is optional text that will appear below the form elements while editing an asset to provide context on the field.',
    'about_custom_fields_title' => 'À propos des champs personnalisés',
    'about_custom_fields_text' => 'Custom fields allow you to add arbitrary attributes to assets.',
    'add_field_to_fieldset' => 'Ajouter un champ au jeu de champs',
    'make_optional' => 'Required - click to make optional',
    'make_required' => 'Optional - click to make required',
    'reorder' => 'Reorder',
    'db_field' => 'Champ BDD',
    'db_convert_warning' => 'WARNING. This field is in the custom fields table as <code> :db_column </code> but should be :expected </code>.'
];
