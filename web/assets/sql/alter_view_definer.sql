use razor;

alter DEFINER = `razor`@`127.0.0.1` VIEW `VIEW_razor_pay_logintimes` AS select `l`.`login_date` AS `login_date`,`l`.`appId` AS `appId`,`l`.`chId` AS `chId`,`l`.`srvId` AS `srvId`,`l`.`version` AS `version`,`l`.`roleId` AS `roleId`,count(1) AS `logintimes` from `razor_login` `l` where `l`.`roleId` in (select `p`.`roleId` from `razor_pay` `p`) group by `l`.`login_date`,`l`.`appId`,`l`.`chId`,`l`.`srvId`,`l`.`version`,`l`.`roleId`;

alter DEFINER = `razor`@`127.0.0.1` VIEW `VIEW_razor_pay_rolelevel` AS select `p`.`pay_date` AS `pay_date`,`p`.`appId` AS `appId`,`p`.`chId` AS `chId`,`p`.`srvId` AS `srvId`,`p`.`version` AS `version`,`p`.`roleLevel` AS `roleLevel`,sum(`p`.`pay_amount`) AS `pay_amount`,count(1) AS `pay_count` from `razor_pay` `p` group by `p`.`pay_date`,`p`.`appId`,`p`.`chId`,`p`.`srvId`,`p`.`version`,`p`.`roleLevel`;

alter DEFINER = `razor`@`127.0.0.1` VIEW `VIEW_razor_pay_count` AS select `p`.`roleId` AS `roleId`,count(1) AS `pay_count` from `razor_pay` `p` group by `p`.`roleId` order by `pay_count` desc;

alter DEFINER = `razor`@`127.0.0.1` VIEW `VIEW_razor_pay_amount_count` AS select `p`.`pay_date` AS `pay_date`,`p`.`appId` AS `appId`,`p`.`chId` AS `chId`,`p`.`srvId` AS `srvId`,`p`.`version` AS `version`,`p`.`roleId` AS `roleId`,sum(`p`.`pay_amount`) AS `pay_amount`,count(1) AS `pay_count` from `razor_pay` `p` group by `p`.`pay_date`,`p`.`appId`,`p`.`chId`,`p`.`srvId`,`p`.`version`,`p`.`roleId`;

alter DEFINER = `razor`@`127.0.0.1` VIEW `VIEW_razor_login_logintimes` AS select `l`.`login_date` AS `login_date`,`l`.`appId` AS `appId`,`l`.`chId` AS `chId`,`l`.`srvId` AS `srvId`,`l`.`version` AS `version`,`l`.`roleId` AS `roleId`,count(1) AS `logintimes` from `razor_login` `l` group by `l`.`login_date`,`l`.`appId`,`l`.`chId`,`l`.`srvId`,`l`.`version`,`l`.`roleId`;

alter DEFINER = `razor`@`127.0.0.1` VIEW `VIEW_razor_createrole_logintimes` AS select `l`.`login_date` AS `login_date`,`l`.`appId` AS `appId`,`l`.`chId` AS `chId`,`l`.`srvId` AS `srvId`,`l`.`version` AS `version`,`l`.`roleId` AS `roleId`,count(1) AS `logintimes` from `razor_login` `l` where `l`.`roleId` in (select `c`.`roleId` from `razor_createrole` `c`) group by `l`.`login_date`,`l`.`appId`,`l`.`chId`,`l`.`srvId`,`l`.`version`,`l`.`roleId`;