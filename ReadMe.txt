$act=a
$uce = ce
$upe = pe
$upa = pa
$utosflag = tosflag
register1 = register1($uce, $upe, $upa, $utosflag)
$pagekeywords, $pagedesc, $pagetitle, $pagecontent, $sitename, $basepath, $baseurl, $admin_email

confirm = confirm($midvalue, $codevalue)
$midvalue = mid
$codevalue = cbcode



mem = mem()
login1 = login1($cbuname, $cbupass)
$cbuname = uemail
$cbupass = upass

saveBasic = saveBasic($st, $sd, $co, $ab, $we)
$st = storetitle
$sd = shortdesc
$co = contact
$ab = about
$we = welcome

showBasic = showBasic()
$template = template
saveTemplate = saveTemplate($template)
chooseTemplate = chooseTemplate()

$cbuname = cbusername
$pid = p
thankyou = thankyou($cbuname, $pid)

changepwd1 = changepwd1($upwdold, $upwdnew, $upwdnew1)
$upwdold = opwd
$upwdnew = npwd
$upwdnew1 = npwd1

changepwd = changepwd()

retpass1 = retpass1($uemail)
$uemail = ueml

retpass = retpass()

$sc = storecurr
$pa = paypalEmail
showInv() = showInv
addCat = addCat()
tsc_config = konfigurasi
tsc_funs = peta
tempForOneUser = temp
savePay = savePay($sc, $pa)
showPay = showPay()
images = photo
bottombanner = bannerbawah
topbanner = banneratas
aff_id = ros_ic
$link = dbconnect();
$r=mysql_query
$q="SELECT * FROM store_info_tab ORDER BY ros_ic"
$scdata = mysql_fetch_array
$sc = $scdata
$ca = $HTTP_POST_VARS['storecat3']
$pagecontent =
$sitename =
$pagetitle =
$pagekeywords =
$pagedesc =
catname = $ca 
saveCat = saveCat($ca)
$itemI
itemID1
itemID2
delCat1 = delCat1($ca)
storecat1
storecat2
storecat3
storeitem1
addItem = addItem($ca, "")
saveItem = saveItem($ica, $iia, $ita, $iua, $iqa, $ipa, $isa, $icaa, $idua, $idea)
editItem = addItem("", $itemI)
delCat = delCat($ca)
delItem = delItem($itemI)
delItem1 = delItem1($itemI)
addThankyou = addThankyou()
saveThankyou = saveThankyou($tn)
promotestore = promotestore()
addBanners = addBanners()
login = login()
$tn = thankyouNote
$ica = itemCat
$iia = itemID
$ita = itemTitle
$iua = imageURL
$iqa = itemQty
$ipa = itemPrice
$isa = itemShip
$icaa = item_category
$idua = item_dwld_url
$idea = itemDet
