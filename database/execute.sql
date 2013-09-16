CREATE TABLE `cart_order_items` (
  `temp_order_id` int(11) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `item_id` int(11) NOT NULL,
  `selected_options` varchar(255) NOT NULL,
  `extra_items` varchar(255) NOT NULL,
  `extra_price` varchar(255) NOT NULL,
  `pizzaname` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `price` decimal(11,2) NOT NULL,
  `pizzatype` varchar(255) NOT NULL,
  `crusttype` varchar(255) NOT NULL,
  `option` varchar(255) NOT NULL,
  `toppingsideA` varchar(255) NOT NULL,
  `toppingsideB` varchar(255) NOT NULL,
  `userid` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `pizzaid` int(11) NOT NULL,
  PRIMARY KEY  (`temp_order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

INSERT INTO `cart_order_items` (`temp_order_id`, `order_id`, `session_id`, `item_id`, `selected_options`, `extra_items`, `extra_price`, `pizzaname`, `size`, `price`, `pizzatype`, `crusttype`, `option`, `toppingsideA`, `toppingsideB`, `userid`, `status`, `pizzaid`) VALUES
(3, 2748359, '32a197366e30ce13b09661d30ba5164c', 105, '32,34', '35,36', '28', '', 0, 12.00, '', '', '', '', '', 11, 'enable', 0),
(4, 1827364, '6cd241a8fdd55ddc8c0f18d73448f0ba', 105, '32', '33,34', '23', '', 0, 12.00, '', '', '', '', '', 11, 'enable', 0),
(5, 1827364, '6cd241a8fdd55ddc8c0f18d73448f0ba', 109, '35', '34,35', '39', '', 0, 24.00, '', '', '', '', '', 11, 'enable', 0),
(6, 1827364, '6cd241a8fdd55ddc8c0f18d73448f0ba', 113, '34', '32,33', '51', '', 0, 45.00, '', '', '', '', '', 11, 'enable', 0),
(7, 1827364, '6cd241a8fdd55ddc8c0f18d73448f0ba', 0, '', '', '', 'Test', 12, 31.00, '', '', '', '', '', 11, 'enable', 67),
(8, 1827364, '6cd241a8fdd55ddc8c0f18d73448f0ba', 0, '', '', '', 'Custom Pizza', 12, 15.00, 'Custom', 'cheese', ',sauce_3 ,cheese_q', 'olive', 'ytftiy,olive', 11, 'enable', 0),
(9, 1613984, 'ab4e79fa2f1824ad31879431ed046de1', 0, '', '', '', 'Test', 12, 31.00, '', '', '', '', '', 11, 'enable', 67),
(10, 1613984, 'ab4e79fa2f1824ad31879431ed046de1', 0, '', '', '', 'Test', 12, 31.00, '', '', '', '', '', 11, 'enable', 67);


CREATE TABLE `orders` (
  `mainorder_id` int(15) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `order_date` date NOT NULL,
  `order_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `time_mode` text NOT NULL,
  `status_deliver` text NOT NULL,
  `status_pickup` text NOT NULL,
  `status_dineup` text NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `add1` varchar(255) NOT NULL,
  `apt_no` int(11) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zip` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `dlinedate` varchar(45) NOT NULL,
  `dlinetime` varchar(25) NOT NULL,
  `appar_avail` varchar(12) NOT NULL,
  `subtotal` decimal(11,2) NOT NULL,
  `combo_dis` decimal(11,2) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_total` decimal(11,2) NOT NULL,
  `coupon_discount` decimal(11,2) NOT NULL,
  `tax` decimal(11,2) NOT NULL,
  `delivery_charge` decimal(11,2) NOT NULL,
  `order_status` int(11) NOT NULL,
  `payment_mode` varchar(12) NOT NULL,
  PRIMARY KEY  (`mainorder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `orders` (`mainorder_id`, `ip_address`, `session_id`, `order_date`, `order_time`, `time_mode`, `status_deliver`, `status_pickup`, `status_dineup`, `first_name`, `last_name`, `email`, `phone`, `add1`, `apt_no`, `city`, `zip`, `userid`, `status`, `dlinedate`, `dlinetime`, `appar_avail`, `subtotal`, `combo_dis`, `guest_id`, `user_id`, `order_total`, `coupon_discount`, `tax`, `delivery_charge`, `order_status`, `payment_mode`) VALUES
(1613984, '117.212.45.179', 'ab4e79fa2f1824ad31879431ed046de1', '2013-09-10', '2013-09-11 05:50:22', '', 'no', 'yes', 'no', 'apple ', 'jam', 'tanvi.geni@gmail.com', '1234-5678-789', '', 0, '', 0, 11, 'enable', '', '', '', 31.00, 0.00, 4, 3, 45.03, 20.00, 4.03, 10.00, 1, 'cod'),
(1827364, '59.89.204.15', '6cd241a8fdd55ddc8c0f18d73448f0ba', '2013-09-10', '2013-09-11 05:50:00', '', 'no', 'yes', 'no', 'apple ', 'jam', 'tanvi.geni@gmail.com', '4567-5678-5678', '', 0, '', 0, 11, 'enable', '', '', '', 159.00, 0.00, 0, 3, 189.67, 20.00, 20.67, 10.00, 1, 'cod'),
(2748359, '116.202.64.146', '32a197366e30ce13b09661d30ba5164c', '2013-09-10', '2013-09-11 05:49:36', '', 'no', 'yes', 'no', 'arti', 'arzoo', 'artiweb@projectpays.com', '123-454-555', '', 0, '', 0, 11, 'enable', '', '', '', 28.00, 0.00, 0, 2, 41.64, 20.00, 3.64, 10.00, 1, 'cod');

CREATE TABLE `usr_mgmnt` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_status` varchar(10) DEFAULT 'disable',
  `status` varchar(255)NOT NULL,
  PRIMARY KEY (`username`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `order_updt_status` (
  `username` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `update_status` varchar(40) NOT NULL,
  PRIMARY KEY (`username`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DELIMITER $$
Create Trigger usr_insert after Insert
on usr_mgmnt
for each row
begin
insert into order_updt_status (`username`,`status`,`update_status`)
values (new.`username`,new.`status`,'new order');
end$$
DELIMITER ;

 