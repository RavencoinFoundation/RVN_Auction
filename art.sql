/*
 Navicat MySQL Data Transfer

 Source Server         : mac-connection
 Source Server Type    : MySQL
 Source Server Version : 50732
 Source Host           : localhost:8889
 Source Schema         : art

 Target Server Type    : MySQL
 Target Server Version : 50732
 File Encoding         : 65001

 Date: 25/03/2021 17:54:17
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for nfts
-- ----------------------------
DROP TABLE IF EXISTS `nfts`;
CREATE TABLE `nfts` (
  `id` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `artist` varchar(255) NOT NULL DEFAULT '',
  `description` varbinary(4000) NOT NULL DEFAULT '',
  `image_url` varchar(1000) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `nft` varchar(60) NOT NULL DEFAULT '',
  `is_sale` int(1) NOT NULL DEFAULT '1',
  `price` int(20) unsigned NOT NULL DEFAULT '1000',
  `status` int(10) NOT NULL DEFAULT '0',
  `lastchange` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  `start` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `auction_end` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `min_bid` int(20) NOT NULL DEFAULT '1',
  `bid_increment` int(20) NOT NULL DEFAULT '1',
  `current_bid` int(20) NOT NULL DEFAULT '0',
  `last_bid_time` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `final_bid` int(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`address`,`nft`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of nfts
-- ----------------------------
BEGIN;
INSERT INTO `nfts` VALUES ('cheating-on-eth', 'Cheating on ETH', 'Weed Thoughts', 0x546869732065706963206D656D652069732076657279206D6574612E20596F752061726520627579696E6720616E204E465420697373756564206F6E20526176656E636F696E2C207368756E6E696E67207468652065786365737369766520474153206F6620457468657265756D206A7573742061732074686973206D616E20697320646F696E672E, 'https://ravencoin.asset-explorer.net/ipfs/Qmb9cJibEU2NyFBbWRMJd2PuvkhNwPR5n6sfdGE3kJVQEZ', 'RFGRqiYGVhQhH9cwyb3n7vBNX8pYVTXr2Y', 'WEEDTHOUGHT#CheatingOnETH', 0, 1000, 2, '2021-03-25 02:10:50.088645', '2021-03-24 23:52:14.890628', '2021-03-26 23:52:14.890628', 1, 1, 0, '2021-03-24 23:52:14.890628', 0);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
