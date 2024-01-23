<?php


namespace App;


 class Constants
 {
     const ORDER_STATUS_PENDING = 'pending';
     const ORDER_STATUS_PROCESSING = 'processing';
     const ORDER_STATUS_SHIPPED = 'shipped';
     const ORDER_STATUS_DELIVERED = 'delivered';
     const ORDER_STATUS_CANCELED = 'canceled';
     const ORDER_STATUS_RETURNED = 'returned';

     public static function getAllStatuses()
     {
         return [
             self::ORDER_STATUS_PENDING,
             self::ORDER_STATUS_CANCELED,
             self::ORDER_STATUS_DELIVERED,
             self::ORDER_STATUS_PROCESSING,
             self::ORDER_STATUS_RETURNED,
             self::ORDER_STATUS_SHIPPED

         ];
     }
 }
