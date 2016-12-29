<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style>
.editDiv div input[type='text']{
	width:70%;
	height:30px;
	max-width:400px;
}
</style>
</head>

<body>
<form method="post" action="profile" enctype="multipart/form-data">   <table width="200" border="0" align="center" cellpadding="12" class="form" style="width:80%;">
                      <tr>
                        <td colspan="2" style="padding-bottom:12px;"><?php echo $msgstat; ?>
</td>
                </tr>
                      <tr>
                        <td colspan="2" class="s_b" style="padding-bottom:24px;"><div style="background:#337ab7; color:#FFF; padding:12px; font-size:14px;"><i class="fa fa-exclamation-circle"></i> It is not compulsory you fill this form at once, take your time and return any time to complete it</div></td>
                </tr>
                      <tr style="padding-top:12px;">
                        <td width="37%" align="left">Full name <span style="color:red">*</span></td>
                        <td width="63%" align="left" valign="middle"><input type="text" name="fln" id="textfield3" value="<?php echo ($_POST['fln'])? $_POST['fln'] : $fulln; ?>" onkeyup="check(this.id,'fn',0);" class="form form-control" style="width:100%;" /></td>
                      </tr>
                      <tr>
                        <td>Sex<span style="color:red"> *</span></td>
                        <td><select id="select2" name="gender" class="form form-control">
                          <option 
                          <?php
						  if ($_POST['gender' ]== "Female" or $gender == "Female")
						  {
							  echo "selected = selected";
						  }
						  ?>
                          >Female</option>
                          <option <?php
						  if ($_POST['gender'] == "Male" or $gender == "Male")
						  {
							  echo "selected = selected";
						  }
						  ?>>Male</option>
                        </select></td>
                      </tr>
                      <tr>
                        <td>Details about you <span style="color:red">*</span></td>
                        <td><textarea name="abt" id="textfield14" onkeyup="check(this.id,'ad',1)" class="form form-control" style="width:100%;" ><?php echo ($_POST['abt'])?  $_POST['abt'] : $about; ?></textarea></td>
                      </tr>
                      <tr>
                        <td>Area of specialization <span style="color:green">*</span></td>
                        <td><input type="text" name="intr" id="textfield4" value="<?php echo ($_POST['genre'])? $_POST['genre'] : $genre; ?>" class="form form-control" style="width:100%;" /></td>
                      </tr>
                      <tr>
                        <td>Contact Address <span style="color:red">*</span></td>
                        <td><textarea name="cad" id="textfield13" onkeyup="check(this.id,'ad',2)" class="form form-control" style="width:100%;" ><?php echo ($_POST['cad'])? $_POST['cad'] : $address; ?></textarea></td>
                      </tr>
                      <tr>
                        <td>Phone Number <span style="color:red">*</span><br />
<span class="small">(use standard input (e.g. +2348100000000, +2347609000000))</span></td>
                        <td><input type="text" name="pho" id="textfield5" value="<?php echo ($_POST['pho'])? $_POST['pho'] : $phone; ?>" onkeyup="check(this.id,'ph',3)" class="form form-control" style="width:100%;" /></td>
                      </tr>
                      <tr>
                        <td colspan="2" class="s_b"><span class="s_h" style="font-weight:bold">Job / Qualification Others</span></td>
                </tr>
                      <tr>
                        <td>Company / Bussiness Name <span style="color:green">*</span></td>
                        <td><input type="text" name="cname" id="textfield6"  value="<?php echo ($_POST['cname'])? $_POST['cname'] : $company; ?>" class="form form-control" style="width:100%;"  /></td>
                      </tr>
                      <tr>
                        <td>Company / Bussiness Address</td>
                        <td><textarea name="compa" id="textfield12" class="form form-control" style="width:100%;" ><?php echo ($_POST['compa'])? $_POST['compa'] : $compadd; ?></textarea>
                          <span style="color:green">*</span></td>
                      </tr>
                      <tr>
                        <td>Website</td>
                        <td><input type="text" name="site" id="textfield9" value="<?php echo ($_POST['site'])? $_POST['site'] : $site; ?>" class="form form-control" style="width:100%;" /></td>
                      </tr>
                      <tr>
                        <td>Position held <span style="color:green">*</span></td>
                        <td><input type="text" name="pos" id="textfield7" value="<?php echo ($_POST['pos'])? $_POST['pos'] : $position; ?>" class="form form-control" style="width:100%;" /></td>
                </tr>
                      <tr>
                        <td colspan="2" class="s_b"><span class="s_h" style="font-weight:bold">Location</span></td>
                </tr>
                      <tr>
                        <td>State<span style="color:red">*</span></td>
                        <td>
                          <select name="state" id="select3" class="form form-control" onchange="matchList(this.id)">
                          <?php
						  $re = mysql_query("select*from states order by state_n asc");
						  while ($mump = (mysql_fetch_assoc($re)))
						  {
						  ?>
                            <option 
                            <?php
                            if ($_POST['state'] == $mump['state_n'] or $state == $mump['state_n'] || $_POST['state'] == $mump['id'] || $state == $mump['id'])
							{
								?>
                                selected="selected"
                                <?php
							}
							?> value="<?php echo $mump['id']; ?>">
							
							<?php echo $mump['state_n']; ?></option>
                            <?php
						  }
						  ?>
                          </select></td>
                      </tr>
                      <tr>
                        <td>City <span style="color:red">*</span></td>
                        <td><span style="color:red" id="manga">
                          <select name="city" id="select4" class="form form-control">
                           <?php
						  $re2 = mysql_query("select*from cities order by city asc");
						  while ($mump2 = (mysql_fetch_assoc($re2)))
						  {
						  ?>
                            <option <?php
                            if ($_POST['city'] == $mump2['city'] or $city == $mump2['city'])
							{
								?>
                                selected="selected"
                                <?php
							}
							?>><?php echo $mump2['city']; ?></option>
                            <?php
						  }
						  ?>
                          </select>
                        </span></td>
                      </tr>
                      <tr>
                        <td colspan="2" class="s_b"><span class="s_h" style="font-weight:bold">Log in details</span></td>
                </tr>
                      <tr>
                        <td>e-mail (must be valid)<span style="color:red"> *</span></td>
                        <td><input type="text" name="mail" id="textfield8" value="<?php echo ($_POST['mail'])? $_POST['mail']: $email; ?>" onMouseOut="pingmail();" onkeyup="check(this.id,'mail',4)" class="form form-control" style="width:100%;" /></td>
                      </tr>
                      <tr>
                        <td colspan="2">Please choose your personal / company's logo Max (250 KiB) <span style="color:red">* </span></td>
                </tr>
                      <tr>
                        <td colspan="2" align="center" valign="middle"><input type="file" name="ffeed" id="ffeed" value="<?php echo $_POST['ffeed']; ?>" style="display:none;" />
                          <div style="cursor:pointer; background:#292031; border:#E6E6E6 thin solid;" onclick="prde('ffeed')" title="Choose image"><img src="bins/drawable-xxhdpi/ic_menu_gallery.png" style="width:auto; height:50px; " /></div></td>
                </tr>
                      <tr>
                        <td colspan="2"><input name="checkbox2" type="checkbox" id="checkbox2" checked="checked" style="width:20px; height:20px;"/> 
                          <span id="wa">I agree with <a href="terms">terms and condition</a> of the website</span></td>
                </tr>
                      <tr>
                        <td colspan="2" align="center"><input type="submit" name="subm" style="display:none;" id="ui" /><div id="sm" title="Register me!" style="background:#4A4466; cursor:pointer;" onclick="run('ui','checkbox2')"><img src="bins/drawable-xxhdpi/ic_menu_invite.png" id="button" value="Register Me!" style="font-weight:bold; padding:10px; width:auto; height:50px;"/> </div></td>
                </tr>
          </table>
          <div class="editDiv">
          <div style="width:">
          <header>Fullname</header>
          <input type="text" name="fln" id="textfield3" value="<?php echo ($_POST['fln'])? $_POST['fln'] : $fulln; ?>" onkeyup="check(this.id,'fn',0);" class="form form-control" style="width:100%;" /></div>
          <div style="width:">
          <header>Sex</header>
          <select id="select2" name="gender" class="form form-control">
                          <option 
                          <?php
						  if ($_POST['gender' ]== "Female" or $gender == "Female")
						  {
							  echo "selected = selected";
						  }
						  ?>
                          >Female</option>
                          <option <?php
						  if ($_POST['gender'] == "Male" or $gender == "Male")
						  {
							  echo "selected = selected";
						  }
						  ?>>Male</option>
                        </select></div>
                        <div>
                        <header>Bio</header>
                        <textarea name="abt" id="textfield14" onkeyup="check(this.id,'ad',1)" class="form form-control" style="width:100%;" ><?php echo ($_POST['abt'])?  $_POST['abt'] : $about; ?></textarea>
                        </div>
                        <div>
                        <header>Specialization</header>
                        <input type="text" name="intr" id="textfield4" value="<?php echo ($_POST['genre'])? $_POST['genre'] : $genre; ?>" class="form form-control" style="width:100%;" />
                        </div>
                        <div>
                        <header>Contact Address</header>
                        <textarea name="cad" id="textfield13" onkeyup="check(this.id,'ad',2)" class="form form-control" style="width:100%;" ><?php echo ($_POST['cad'])? $_POST['cad'] : $address; ?></textarea>
                        </div>
                        <div>
                        <header>Phone Number</header>
                        <input type="text" name="pho" id="textfield5" value="<?php echo ($_POST['pho'])? $_POST['pho'] : $phone; ?>" onkeyup="check(this.id,'ph',3)" class="form form-control" style="width:100%;" />
                        </div>
                        <div class="s_h" style="font-weight:bold; padding:10px;">Job / Qualification Others</div>
                        <div>
                        <header>Company / Bussiness Name</header>
                        <input type="text" name="cname" id="textfield6"  value="<?php echo ($_POST['cname'])? $_POST['cname'] : $company; ?>" class="form form-control" style="width:100%;"  />
                        </div>
                        <div>
                        <header>Company / Bussiness Address</header>
                        <textarea name="compa" id="textfield12" class="form form-control" style="width:100%;" ><?php echo ($_POST['compa'])? $_POST['compa'] : $compadd; ?></textarea>
                        </div>
                        <div>
                        <header>Website</header>
                        <input type="text" name="site" id="textfield9" value="<?php echo ($_POST['site'])? $_POST['site'] : $site; ?>" class="form form-control" style="width:100%;" />
                        </div>
                        <div>
                        <header>Position</header>
                        <input type="text" name="pos" id="textfield7" value="<?php echo ($_POST['pos'])? $_POST['pos'] : $position; ?>" class="form form-control" style="width:100%;" />
                        </div>
                        <div class="s_h" style="font-weight:bold; padding:10px;">Location</div>
                        <div>
                        <header>State</header>
                        <select name="state" id="select3" class="form form-control" onchange="matchList(this.id)">
                          <?php
						  $re = mysql_query("select*from states order by state_n asc");
						  while ($mump = (mysql_fetch_assoc($re)))
						  {
						  ?>
                            <option 
                            <?php
                            if ($_POST['state'] == $mump['state_n'] or $state == $mump['state_n'] || $_POST['state'] == $mump['id'] || $state == $mump['id'])
							{
								?>
                                selected="selected"
                                <?php
							}
							?> value="<?php echo $mump['id']; ?>">
							
							<?php echo $mump['state_n']; ?></option>
                            <?php
						  }
						  ?>
                          </select>
                        </div>
            <div>
              <header>City</header>
                        <select name="city2" id="city" class="form form-control">
                          <?php
						  $re2 = mysql_query("select*from cities order by city asc");
						  while ($mump2 = (mysql_fetch_assoc($re2)))
						  {
						  ?>
                          <option <?php
                            if ($_POST['city'] == $mump2['city'] or $city == $mump2['city'])
							{
								?>
                                selected="selected"
                                <?php
							}
							?>><?php echo $mump2['city']; ?></option>
                          <?php
						  }
						  ?>
                        </select>
              </div>
              <div class="s_h" style="font-weight:bold; padding:10px;">Log in detail</div>
                        <div>
                        <header>e mail</header>
                        <input type="text" name="mail" id="textfield8" value="<?php echo ($_POST['mail'])? $_POST['mail']: $email; ?>" onMouseOut="pingmail();" onkeyup="check(this.id,'mail',4)" class="form form-control" style="width:100%;" />
                        </div>
                        <div>
                        <header>Choose a logo</header>
                        <input type="file" name="ffeed" id="ffeed" value="<?php echo $_POST['ffeed']; ?>" />
                        </div>
                        
                        <div>
                        <button name="edit" class="btn btn-primary">Update</button>
                        </div>
          </div>
          
          </form>
</body>
</html>