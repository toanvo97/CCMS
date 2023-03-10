<?php

return array(

    'does_not_exist' => 'The accessory [:id] does not exist.',
    'assoc_users'	 => 'This accessory currently has :count items checked out to users. Please check in the accessories and and try again. ',

    'create' => [
        'error'   => 'The accessory was not created, please try again.',
        'success' => 'The accessory was successfully created.',
    ],

    'update' => [
        'error'   => 'The accessory was not updated, please try again',
        'success' => 'The accessory was updated successfully.',
    ],

    'delete' => [
        'confirm'   => 'Are you sure you wish to delete this accessory?',
        'error'   => 'There was an issue deleting the accessory. Please try again.',
        'success' => 'The accessory was deleted successfully.',
    ],

     'checkout' => [
        'error'   		=> 'Accessory was not checked out, please try again',
        'success' 		=> 'Accessory checked out successfully.',
        'user_does_not_exist' => 'That user is invalid. Please try again.',
    ],

    'checkin' => [
        'error'   		=> 'Accessory was not checked in, please try again',
        'success' 		=> 'Accessory checked in successfully.',
        'user_does_not_exist' => 'That user is invalid. Please try again.',
    ],

);
