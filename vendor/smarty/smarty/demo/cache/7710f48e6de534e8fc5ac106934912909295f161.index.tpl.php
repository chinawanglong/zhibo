<?php
/*%%SmartyHeaderCode:185026625059e02505c3d3a8_30418425%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7710f48e6de534e8fc5ac106934912909295f161' => 
    array (
      0 => '/usr/local/apache2/htdocs/zhibo/vendor/smarty/smarty/demo/templates/index.tpl',
      1 => 1440034966,
      2 => 'file',
    ),
    '2fcb747fb739aeae4081e4d425734b9c11dd6f61' => 
    array (
      0 => '/usr/local/apache2/htdocs/zhibo/vendor/smarty/smarty/demo/configs/test.conf',
      1 => 1440034966,
      2 => 'file',
    ),
    '7a280d17a460822cb839afad6a48e1cd4c759bd2' => 
    array (
      0 => '/usr/local/apache2/htdocs/zhibo/vendor/smarty/smarty/demo/templates/header.tpl',
      1 => 1440034966,
      2 => 'file',
    ),
    '2cf4e7dab9d0851c60ae359279d902d5b7e7c2b7' => 
    array (
      0 => '/usr/local/apache2/htdocs/zhibo/vendor/smarty/smarty/demo/templates/footer.tpl',
      1 => 1440034966,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '185026625059e02505c3d3a8_30418425',
  'tpl_function' => 
  array (
  ),
  'version' => '3.1.27',
  'unifunc' => 'content_59e07a00cde6c5_68136036',
  'has_nocache_code' => true,
  'cache_lifetime' => 120,
),true);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_59e07a00cde6c5_68136036')) {
function content_59e07a00cde6c5_68136036 ($_smarty_tpl) {
?>
<HTML>
<HEAD>
<TITLE>foo - <?php echo $_smarty_tpl->tpl_vars['Name']->value;?>
</TITLE>
</HEAD>
<BODY bgcolor="#ffffff">


<PRE>


    <b>        
        Title: Welcome To Smarty!
        </b>
    The current date and time is 2017-10-13 08:32:00

    The value of global assigned variable $SCRIPT_NAME is /zhibo/vendor/smarty/smarty/demo/index.php

    Example of accessing server environment variable SERVER_NAME: aob.meiling123.com

    The value of {$Name} is <b><?php echo $_smarty_tpl->tpl_vars['Name']->value;?>
</b>

variable modifier example of {$Name|upper}

<b><?php echo mb_strtoupper($_smarty_tpl->tpl_vars['Name']->value, 'UTF-8');?>
</b>


An example of a section loop:

                        1 * John Doe
                                    2 * Mary Smith
                                    3 . James Johnson
                                    4 . Henry Case
                
    An example of section looped key values:

            phone: 1
        <br>

            fax: 2
        <br>

            cell: 3
        <br>
            phone: 555-4444
        <br>

            fax: 555-3333
        <br>

            cell: 760-1234
        <br>
        <p>

        testing strip tags
        <table border=0><tr><td><A HREF="/zhibo/vendor/smarty/smarty/demo/index.php"><font color="red">This is a test </font></A></td></tr></table>

</PRE>

This is an example of the html_select_date function:

<form>
    <select name="Date_Month">
<option value="01">January</option>
<option value="02">February</option>
<option value="03">March</option>
<option value="04">April</option>
<option value="05">May</option>
<option value="06">June</option>
<option value="07">July</option>
<option value="08">August</option>
<option value="09">September</option>
<option value="10" selected="selected">October</option>
<option value="11">November</option>
<option value="12">December</option>
</select>
<select name="Date_Day">
<option value="1">01</option>
<option value="2">02</option>
<option value="3">03</option>
<option value="4">04</option>
<option value="5">05</option>
<option value="6">06</option>
<option value="7">07</option>
<option value="8">08</option>
<option value="9">09</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13" selected="selected">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
</select>
<select name="Date_Year">
<option value="1998">1998</option>
<option value="1999">1999</option>
<option value="2000">2000</option>
<option value="2001">2001</option>
<option value="2002">2002</option>
<option value="2003">2003</option>
<option value="2004">2004</option>
<option value="2005">2005</option>
<option value="2006">2006</option>
<option value="2007">2007</option>
<option value="2008">2008</option>
<option value="2009">2009</option>
<option value="2010">2010</option>
</select>
</form>

This is an example of the html_select_time function:

<form>
    <select name="Time_Hour">
<option value="01">01</option>
<option value="02">02</option>
<option value="03">03</option>
<option value="04">04</option>
<option value="05">05</option>
<option value="06">06</option>
<option value="07">07</option>
<option value="08" selected="selected">08</option>
<option value="09">09</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
</select>
<select name="Time_Minute">
<option value="00">00</option>
<option value="01">01</option>
<option value="02">02</option>
<option value="03">03</option>
<option value="04">04</option>
<option value="05">05</option>
<option value="06">06</option>
<option value="07">07</option>
<option value="08">08</option>
<option value="09">09</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
<option value="32" selected="selected">32</option>
<option value="33">33</option>
<option value="34">34</option>
<option value="35">35</option>
<option value="36">36</option>
<option value="37">37</option>
<option value="38">38</option>
<option value="39">39</option>
<option value="40">40</option>
<option value="41">41</option>
<option value="42">42</option>
<option value="43">43</option>
<option value="44">44</option>
<option value="45">45</option>
<option value="46">46</option>
<option value="47">47</option>
<option value="48">48</option>
<option value="49">49</option>
<option value="50">50</option>
<option value="51">51</option>
<option value="52">52</option>
<option value="53">53</option>
<option value="54">54</option>
<option value="55">55</option>
<option value="56">56</option>
<option value="57">57</option>
<option value="58">58</option>
<option value="59">59</option>
</select>
<select name="Time_Second">
<option value="00" selected="selected">00</option>
<option value="01">01</option>
<option value="02">02</option>
<option value="03">03</option>
<option value="04">04</option>
<option value="05">05</option>
<option value="06">06</option>
<option value="07">07</option>
<option value="08">08</option>
<option value="09">09</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
<option value="32">32</option>
<option value="33">33</option>
<option value="34">34</option>
<option value="35">35</option>
<option value="36">36</option>
<option value="37">37</option>
<option value="38">38</option>
<option value="39">39</option>
<option value="40">40</option>
<option value="41">41</option>
<option value="42">42</option>
<option value="43">43</option>
<option value="44">44</option>
<option value="45">45</option>
<option value="46">46</option>
<option value="47">47</option>
<option value="48">48</option>
<option value="49">49</option>
<option value="50">50</option>
<option value="51">51</option>
<option value="52">52</option>
<option value="53">53</option>
<option value="54">54</option>
<option value="55">55</option>
<option value="56">56</option>
<option value="57">57</option>
<option value="58">58</option>
<option value="59">59</option>
</select>
<select name="Time_Meridian">
<option value="am" selected="selected">AM</option>
<option value="pm">PM</option>
</select>
</form>

This is an example of the html_options function:

<form>
    <select name=states>
        <option value="NY">New York</option>
<option value="NE" selected="selected">Nebraska</option>
<option value="KS">Kansas</option>
<option value="IA">Iowa</option>
<option value="OK">Oklahoma</option>
<option value="TX">Texas</option>

    </select>
</form>

</BODY>
</HTML>

<?php }
}
?>