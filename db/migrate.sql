# destoon_category -> iok_category | product
insert into webv2.iok_category (`id`, `categorytype`, `prettyname`, `cmsdirectory`, `linkurl`, `parentid`, `arrparentid`, `child`, `arrchildid`, `listorder`)
select catid,'8',catname,catdir,linkurl,parentid,arrparentid,`child`, `arrchildid`, `listorder` from
destoon_category where moduleid=16 order by catid;
 
# destoon_category -> iok_category | article
# 8786 媒体报道
# 8189 我行出品
# 8181 企业人物（放其他所有的数据）
# 专题，关于我们单独添加
insert into webv2.iok_category (`id`, `categorytype`, `prettyname`, `cmsdirectory`, `linkurl`, `parentid`, `arrparentid`, `child`, `arrchildid`, `listorder`)
select catid, '1', catname, catdir, linkurl, parentid, arrparentid, `child`, `arrchildid`, `listorder` from
destoon_category where moduleid=21 and catid in(8181,8786,8185,8189) order by catid;

# destoon_article21 -> iok_article | 8786,8189
insert into webv2.iok_article (`id`, `categoryid`, `title`, `content`, `introduce`, `tag`, `author`, `copyfrom`, `hits`, `thumb`, `ip`,  `addtime`, `adduserid`) 
select a.itemid, a.catid, a.title,b.content,a.introduce,a.tag, a.author,a.copyfrom,a.hits,a.thumb,a.ip,a.addtime,1 from
destoon_article_21 a inner join destoon_article_data_21 b on a.itemid=b.itemid where a.status=3 and a.catid in(8786,8189);
 
# destoon_article21 -> iok_article | not in(8786,8189) to 8181
insert into webv2.iok_article (`id`, `categoryid`, `title`, `content`, `introduce`, `tag`, `author`, `copyfrom`, `hits`, `thumb`, `ip`,  `addtime`, `adduserid`) 
select a.itemid, 8181, a.title,b.content,a.introduce,a.tag, a.author,a.copyfrom,a.hits,a.thumb,a.ip,a.addtime,1 from
destoon_article_21 a inner join destoon_article_data_21 b on a.itemid=b.itemid where a.status=3 and a.catid not in (8786,8189);
 
# destoon_type -> iok_membercategory | 
insert into webv2.iok_membercategory (`id`, `prettyname`, `listorder`, memberid)
select typeid, typename, `listorder`, substr(item, 6) from
destoon_type where item like 'mall%' order by typeid;

# destoon_member -> iok_member
# 1,channel
insert into iok_member (`id`, `account`, `oldpasshash`, `membertype`, `gradeid`, `agentareaid`, `areaid`, `servicestaffid`, `inviterid`, `logincount`, `enabled`, `registerip`, `registertime`, `loginip`, `logintime`, `addtime`, `adduserid`, `updatetime`, `updateuserid`)
select m.userid,m.username,m.`password`, 'company', (m.regid-11) , m.aid, m.areaid, m.representid,iv.userid,m.logintimes,1,m.regip,m.regtime,m.loginip,m.logintime,m.regtime,null,m.edittime,null from newb2b1211.destoon_member m left join newb2b1211.destoon_member iv on m.inviter=iv.username 
where m.groupid = 1 and m.regid in (12,13,14);

insert into iok_memberinfo (`memberid`, `prettyname`, `gender`, `mobile`, `email`, `qq`, `telephone`, `fax`, `addrareaid`, `address`, `postcode`,  `website`)
select m.userid,m.truename,if(m.gender=1,'male','female'),m.mobile,m.email,m.qq,c.telephone,c.fax,c.head_areaid,c.address,c.postcode,c.homepage from newb2b1211.destoon_member m inner join newb2b1211.destoon_company c on m.userid=c.userid 
where m.groupid = 1 and m.regid in (12,13,14);

insert into iok_membercompany (`memberid`, `company`, `companytypeid`, `categoryids`, `capital`, `regunit`,  `productsell`, `productbuy`, `business`, `content`)
select m.userid,c.company,ct.id as companyid,c.catid,c.capital,'renminbi',c.sell,c.buy,c.business,cc.content
from newb2b1211.destoon_member m inner join newb2b1211.destoon_company c on m.userid=c.userid 
inner join newb2b1211.destoon_company_data cc on c.userid=cc.userid
left join webv2.iok_membercompanytype ct on c.type=ct.prettyname
where m.groupid = 1 and m.regid in (12,13,14)

# 2,service staff
insert into iok_member (`id`, `account`, `oldpasshash`, `membertype`, `gradeid`, agentareaid,`areaid`, `logincount`, `enabled`, `registerip`, `registertime`, `loginip`, `logintime`, `addtime`, `adduserid`, `updatetime`)
select m.userid,m.username,m.`password`, 'person', if(r.grade=1,5,4) , m.areaid,m.areaid, m.logintimes,1,m.regip,m.regtime,m.loginip,m.logintime,m.regtime,null,m.edittime from newb2b1211.destoon_member m inner join newb2b1211.destoon_represent r on m.userid=r.userid 
where m.groupid=15;

insert into iok_memberinfo (`memberid`, idnumber,`prettyname`, `gender`, `mobile`, `email`, `qq`, `addrareaid`, `address`)
select m.userid,m.idcard,m.truename,if(m.gender=1,'male','female'),m.mobile,m.email,m.qq,0,m.address from newb2b1211.destoon_member m inner join newb2b1211.destoon_represent r on m.userid=r.userid 
where m.groupid = 15;

# headpic
insert into iok_memberproof(`memberid`, `prooftypeid`, `imageurl`, `uploadid`, `enabled`, `deleted`, `addtime`)
select r.userid,4,r.headpic, 0, 1,0,m.regtime from newb2b1211.destoon_member m inner join newb2b1211.destoon_represent r on m.userid=r.userid 
where m.groupid = 15;
# idcard1
insert into iok_memberproof(`memberid`, `prooftypeid`, `imageurl`, `uploadid`, `enabled`, `deleted`, `addtime`)
select r.userid,1,r.idcardimg, 0, 1,0,m.regtime from newb2b1211.destoon_member m inner join newb2b1211.destoon_represent r on m.userid=r.userid 
where m.groupid = 15;
# idcard2
insert into iok_memberproof(`memberid`, `prooftypeid`, `imageurl`, `uploadid`, `enabled`, `deleted`, `addtime`)
select r.userid,2,r.idcardimg2, 0, 1,0,m.regtime from newb2b1211.destoon_member m inner join newb2b1211.destoon_represent r on m.userid=r.userid 
where m.groupid = 15;

# 3，company

# 4，person 









# destoon_mall -> iok_product
insert into iok_product (`id`, `categoryid`, `customcategoryid`, `custombrandid`, memberid, `title`, `price`, `referenceprice`, `unit`, `commission`, `inventory`, `moq`, `isfreedelivery`, `deliverydays`, `model`, `material`, `placeareaid`, `shipareaid`, `content`, `listorder`, `hits`, `thumb`, `thumb1`, `thumb2`, `uploadid1`, `uploadid2`, `uploadid3`, `ip`, `status`)
select m1.itemid, catid, mycatid, 0, m.userid, title, price, 0, unit, commission, amount, minamount, 0, days, type, quality, place, sendplace, md.content, 0, 0, thumb, thumb1, thumb2, 0, 0, 0, m1.ip, m1.status
from newb2b1211.destoon_mall m1 inner join newb2b1211.destoon_member m on m1.username=m.username
inner join newb2b1211.destoon_mall_data md on m1.itemid=md.itemid;



# destoon_finance_record -> iok_logfinance
insert into iok_logfinance(id, memberid, areaid, orderid, type, bank,amount,balance,reason,note,addtime)
select f.itemid, u.userid, u.areaid,f.orderid,if(f.mark=0,'recharge',if(f.mark=1,'transferin',if(f.mark=2,'drawcash',if(f.mark=3,'outcome',if(f.mark=4,'income',if(f.mark=5,'transferout','')))))),f.bank,f.amount,f.balance,f.reason,f.note,f.addtime
from newb2b1211.destoon_finance_record f inner join newb2b1211.destoon_member u on f.username=u.username
where u.groupid!=0;

# destoon_member -> iok_member
# 1,channel
insert into iok_member (`id`, `account`, `oldpasshash`, `membertype`, `gradeid`, `agentareaid`, `areaid`, `servicestaffid`, `inviterid`, `logincount`, `enabled`, `registerip`, `registertime`, `loginip`, `logintime`, `addtime`, `adduserid`, `updatetime`, `updateuserid`)
select m.userid,m.username,m.`password`, 'company', (m.regid-11) , m.aid, m.areaid, m.representid,iv.userid,m.logintimes,1,m.regip,m.regtime,m.loginip,m.logintime,m.regtime,null,m.edittime,null from newb2b1211.destoon_member m left join newb2b1211.destoon_member iv on m.inviter=iv.username 
where m.groupid = 1 and m.regid in (12,13,14);

insert into iok_memberinfo (`memberid`, `prettyname`, `gender`, `mobile`, `email`, `qq`, `telephone`, `fax`, `addrareaid`, `address`, `postcode`,  `website`)
select m.userid,m.truename,if(m.gender=1,'male','female'),m.mobile,m.email,m.qq,c.telephone,c.fax,c.head_areaid,c.address,c.postcode,c.homepage from newb2b1211.destoon_member m inner join newb2b1211.destoon_company c on m.userid=c.userid 
where m.groupid = 1 and m.regid in (12,13,14);

insert into iok_membercompany (`memberid`, `company`, `companytypeid`, `categoryids`, `capital`, `regunit`,  `productsell`, `productbuy`, `business`, `content`)
select m.userid,c.company,ct.id as companyid,c.catid,c.capital,'renminbi',c.sell,c.buy,c.business,cc.content
from newb2b1211.destoon_member m inner join newb2b1211.destoon_company c on m.userid=c.userid 
inner join newb2b1211.destoon_company_data cc on c.userid=cc.userid
left join webv2.iok_membercompanytype ct on c.type=ct.prettyname
where m.groupid = 1 and m.regid in (12,13,14)

# 2,service staff
insert into iok_member (`id`, `account`, `oldpasshash`, `membertype`, `gradeid`, agentareaid,`areaid`, `logincount`, `enabled`, `registerip`, `registertime`, `loginip`, `logintime`, `addtime`, `adduserid`, `updatetime`)
select m.userid,m.username,m.`password`, 'person', if(r.grade=1,5,4) , m.areaid,m.areaid, m.logintimes,1,m.regip,m.regtime,m.loginip,m.logintime,m.regtime,null,m.edittime from newb2b1211.destoon_member m inner join newb2b1211.destoon_represent r on m.userid=r.userid 
where m.groupid=15;

insert into iok_memberinfo (`memberid`, idnumber,`prettyname`, `gender`, `mobile`, `email`, `qq`, `addrareaid`, `address`)
select m.userid,m.idcard,m.truename,if(m.gender=1,'male','female'),m.mobile,m.email,m.qq,0,m.address from newb2b1211.destoon_member m inner join newb2b1211.destoon_represent r on m.userid=r.userid 
where m.groupid = 15;

# headpic
insert into iok_memberproof(`memberid`, `prooftypeid`, `imageurl`, `uploadid`, `enabled`, `deleted`, `addtime`)
select r.userid,4,r.headpic, 0, 1,0,m.regtime from newb2b1211.destoon_member m inner join newb2b1211.destoon_represent r on m.userid=r.userid 
where m.groupid = 15;
# idcard1
insert into iok_memberproof(`memberid`, `prooftypeid`, `imageurl`, `uploadid`, `enabled`, `deleted`, `addtime`)
select r.userid,1,r.idcardimg, 0, 1,0,m.regtime from newb2b1211.destoon_member m inner join newb2b1211.destoon_represent r on m.userid=r.userid 
where m.groupid = 15;
# idcard2
insert into iok_memberproof(`memberid`, `prooftypeid`, `imageurl`, `uploadid`, `enabled`, `deleted`, `addtime`)
select r.userid,2,r.idcardimg2, 0, 1,0,m.regtime from newb2b1211.destoon_member m inner join newb2b1211.destoon_represent r on m.userid=r.userid 
where m.groupid = 15;

# 3，company

# 4，person 



