<?ob_start();?>
<html>
<?
include('../lib/db_connect.php');
$connect=dbconn(); //DB����Ʈ
$member=member();  //ȸ������

//if($member[user_id]!="admin")Error("������ �޴��Դϴ�.");
include('../lib/lib.php'); //�ð��Լ���
include('../_header.php');

$Search_text=$_GET[Search_text];
$dy=$_GET[Search_year];
$dm=$_GET[Search_month];
?>

<form action='<?=$PHP_SELE?>'>
   <td height='20' colspan='5' bgcolor='#FFFFFF' align='right'>
        <label class="btn btn-default " >�������� �� ��ȸ &nbsp;
        <select name='Search_year' class="input-sm">
            <option value='2017'>2017��
            <option value='2018'>2018��
            <option value='2019'>2019��
            <option value='2020'>2020��
        </select>

        <select name='Search_month' class="input-sm">
            <option value=''>������
            <option value='01'>1��
            <option value='02'>2��
            <option value='03'>3��
            <option value='04'>4��
            <option value='05'>5��
            <option value='06'>6��
            <option value='07'>7��
            <option value='08'>8��
            <option value='09'>9��
            <option value='10'>10��
            <option value='11'>11��
            <option value='12'>12��
        </select>
     ����ȸ
        <input type='text' name='Search_text' size='25' class="input-sm" placeholder="�ش糯¥/�����.. ">
        <input class="btn btn-inverse btn-sm" type='submit' value='Search'>
</form>
<table class="table">
     <tr>
     <td>��ȣ</td><td>����</td><td>��������</td><td>����������</td><td>����ݾ�</td>
</tr>
<?


$where="(pay_name like '%$Search_text%' or pay_date like '%$Search_text%' or name like '%$Search_text%'
      or amount like '%$Search_text%' or ctr_no like '%$Search_text%' or pay_state like '%$Search_text%'
      or period like '%$Search_text%' or ctr_name like '%$Search_text%' or period like '%$Search_text%'
)";

$dd="01";
echo "<br>".$dy."�� ".$dm."�� ���� ��ȸ�Դϴ�. <br>";
$total=0;$cnt=1;$sum=0;
for ($i=1; $i <32; $i++) {
$di=computeMonth($dy,$dm,$i,0);
$query ="select * from payment where pay_date='$di' and $where order by name asc, pay_type desc";
$result=mysql_query($query,$connect);
while($data= mysql_fetch_array($result)){?>

     <tr><td><?=$cnt?></td><td><?=$data[name]?></td><td><?=$data[pay_name]?></td><td><?=$data[pay_date]?></td><td><?=number_format($data[amount])?></td></tr>

<?
        $total++; $cnt++; $sum+=$data[amount];
}//while�� ��
}

echo "�Ѱ�����=".$total."<br>";
echo "�����հ�´�=".number_format($sum)."<br>";




//�ϴ� ��� �������̺���.. �������� �˻��ؼ�
//1.���� ���ΰ�� ������..
//2.���߿� ���� ���ΰ�� ������
//3. ���߿� ���� ����� ����..
//4. �״��� �� ���Ѵ�.
//��¥�� ���� ���ؼ� �ٸ��� ���� , ������ �н�


?>
</table>

��ϵǾ����ϴ�.




<!--
mktime(��,��,��,��,��,��) ���н� Ÿ��(Ÿ�ӽ�����:1970�����������κ��� 1�ʴ�������)���� ���� ����մϴ�.
Ÿ�ӽ������� ��¥�������� ���� �ִ� �Լ��� date �Դϴ�.
date�� ��¥�� ���� ������ �⺻������ ������ ���մϴ�.
-- date("Y-m-d") ==> ���� ��¥

mktime ���� ���� ���� date �Լ��� Ư�� �������� ���
date("Y-m-d", mktime(0, 0, 0, 12, 32, 1997)); ==> 1998-01-01

����� �Ҷ� ���� ���� 1��32���� 2��1 �Ϸ� ���´ٴ� ���Դϴ�.
�׷� 2005�� 1������ 100�� ���� ���� �����ϱ��?
���� date("Y-m-d", mktime(0, 0, 0, 0 , 1, 101, 2005)); ==> 2005�� 04�� 11��
(1��1���� �����ϸ� �ȵǰ���? �׷��� �Ϸ� �� ����~)
����� �⺻�Դϴ�.

���.
�⺻������ strtotime("��������") ���� �մϴ�.
Ÿ�ӽ������� �����մϴ�.
�̸��� date ������ ����� �� �ִٴ� ���Դϴ�. ^^

strtotime �� ��¥�� ���� ������ �⺻������ ������ ���մϴ�.
�׸��� �� �Լ� ���� �ϼ��� �Ѿ�� �����޷� ���˴ϴ�.

----- strtotime("+3 day") => ���ÿ��� 3�� ��, ���� ���� �Ѿ�� 1�Ϸ� ����
�� �Լ��� ���������� �����ϴ� ������ mktime �� ����� �ʿ䰡 ���ٴ� ���Դϴ�.
(�ʿ䰡 ���� ��츦 ã���ּ���. ��_-+)

date("Y�� m�� d�� h:m:s",mktime(12,12,1,1,2,2005))
---date("Y�� m�� d�� h:m:s",strtotime("2005-01-02 12:12:01"))

�� �� �Լ��� ���� 2005�� 01�� 02�� 12:01:01 �� ��Ÿ���ϴ�.
���� ����ϱ⵵ strtotime �� �ξ� �����ϴ�.

�׷� 2005�� 1������ 100�� ���� ���� �������� strtotime �� �̿��ؼ� Ȯ���غ��ô�.
--���� date("Y-m-d", strtotime("2005-01-01 +100 day")); ==> 2005�� 04�� 11��
������
+100 day �� +2 month �� +10 year �� ���� Ư�� ������ �����մϴ�.
�׷��� ���� ������ ���Դϴ�. ��_-+

�γ�¥�� ������ Ÿ�ӽ������� �γ�¥�� ���̰��� �� 86400 (60��*60��*24��) �� ������ �������� ���ɴϴ�.
---intval((strtotime("2005-01-10")-strtotime("2005-01-02"))/86400)    =>    8

�̸��ϸ� PHP ���� ������ ��¥ ����� �Ͻ� �� �ֽ��ϴ�.

------------------------------------
$startDate="2006-05-01";
$endDate="2006-07-01";
�����ڸ� ����Ͽ� �迭�� ����ϴ�.
$arrStartDate=explode("-",$startDate);
$arrEndDate=explode("-",$endDate);
$startTime=mktime(0,0,0,$arrStartDate[1],$arrStartDate[2],$arrStartDate[0]);
$endTime=mktime(0,0,0,$arrEndDate[1],$arrEndDate[2],$arrEndDate[0]);

echo "�� ��¥�� ���̴� ";
echo NUMBER_FORMAT(intval(($endTime-$startTime)/86400));
echo "�� �Դϴ�";


 -->
