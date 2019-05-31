<?php

/*
1. update user details by contact id
(full billing address)
2. Get Cards
3. Add Card
4. Create Blank Order
5. Add Order Items
6. Assign a Tag
7. Query Table
*/

namespace App\Http\Controllers;

use App\Services\InfusionSoftServices;
use App\Services\InfusionsoftV2\InfusionsoftFactory;
use App\Services\Customers\Account\InfusionsoftCustomer;
use App\Services\Customer;
use Request;

class TestINFS extends Controller
{
    //
    public $infs = null;

	public function __construct(){
        // $this->infs = new InfusionSoftServices;
	}

    public function query_table($table_name="", $query=array(), $return=false){
        if($table_name == "") $table_name = "Contact";
        if(count($query) == 0) $query = array("Email"=>"sunil@fusedsoftware.com");

        $return_data = $this->infs->queryTable($table_name, $query, $return);
        echo "<pre>"; print_r($return_data); echo "</pre>"; 
    }

    public function get_product($table_name="", $query=array(), $return=false){
        if($table_name == "") $table_name = "Product";
        if(count($query) == 0) $query = array("Id"=>"%%");

        $return_data = $this->infs->queryTable($table_name, $query, $return);
        echo "<pre>"; print_r($return_data); echo "</pre>"; 
    }

    public function test_cutover(int $userid)
    {
        // $pendingBilling = new \App\Services\Customers\Account\Billing\PendingBilling($userid);

        // echo $pendingBilling->getTotal();

        // $currentDate = new \DateTime('2019-05-01');
        // $nextDate = new \DateTime('2019-05-08');

        $currentDate = date('Y-m-d', strtotime('2019-05-01 +1 week'));

        // $diff = $currentDate->diff($nextDate);

        // echo $diff->format('%R%a') == '+1';

        echo $currentDate;
    }

    public function test_connection(){


        // $infusionsoftCustomer = new InfusionsoftCustomer(29,'inline');
        // $infusionsoftCustomer->updateCustomerInfs();
        // die();

        // $infusionsoft = new \App\Services\Customers\Account\InfusionsoftCustomer(6);
        // $date = new \DateTime('2019-02-07');

        // $infusionsoft->updateContact();
        // $infusionsoft->savedTagToContact(129, array(13));
        // $infusionsoft->updateStatus();
        // $infusionsoft->updatePausedCancelledPlans('Plan Test', $date);
        // $infusionsoft->updateCustomerInfs();
        // $infusionsoft->updateCustomerDeliveryDetailsInfs();
        // $infusionsoft->updateCustomerDeliveryLocationWithAddressOnlyInfs();
        // $infusionsoft->updateCustomerDeliveryMenuOnlyInfs();
        

        // die();
        // $tag = new \App\Services\InfusionsoftV2\CustomField;

        // print_r(new \DateTime(date('2019-01-01')));
        // echo "<pre>";
        // print_r($tag->getDeliveryAddress());
        // print_r($tag->getActiveDeliveryAddressId());die();

        // new \Datetime(strtotime(date('now')))
        // $failedBilling = new \App\Services\Customers\BillingIssue\FailedBilling(
        //     // new \Datetime('2019-01-2')
        //     new \Datetime(strtotime(date('now')))
        // );
        // echo "<pre>";
        // $failedBilling->handle();

        // die();

        // $autoEmail = new \App\Services\Reports\AutoEmail\AutoEmail('Last Cycle', 2);
        // $autoEmail->handle();
        // die();

        // $this->infs->fetchContact();
        // $this->infusionsoft = (new InfusionsoftFactory('oauth2'))->service();
        // print_r($this->infusionsoft->fetchContactById(13));



        $this->api = (new \App\Services\InfusionsoftV2\InfusionsoftFactory('oauth2'))->service();
        // $this->api = new \App\Services\InfusionSoftServices;

        // echo "<pre>";
        // print_r($this->api->getOrderItems('1581'));
        // die();
        
        $contactId = 13;

        $contact = array(
            "Email" => 'vic+testlib+01@gmail.com',
            "FirstName" => 'Vic',
            "LastName" => 'Test LiB 01',
            "Phone1" => '09051149242',
            "State" => 'Australia',
            "Country" => 'Australia',
            "City" => 'Melbourne',
            "StreetAddress1" => 'Address 1',
            "StreetAddress2" => 'Address 2',
            "PostalCode" => '2001',
            "DateCreated" => date('Y-m-d H:i:s')
        );

        $card = array(
            "ContactId" => $contactId, 
            "NameOnCard" => "James R Jackson", 
            "CardNumber" => "4645790045598017", 
            "ExpirationMonth" => "05", 
            "ExpirationYear" => "2021", 
            "CVV2" => "188",
            "FirstName" => 'Vic',
            "LastName" => 'Test LiB 01',
            'CardType' => 4,
            'BillName' => 'James R Jackson',
            'BillAddress1' => 'Address 1',
            'BillAddress2' => 'Address 2',
            'BillCity' => 'Melbourne',
            'BillState' => 'Australia',
            'BillZip' => '3000',
            'BillCountry'  => 'Australia',
            'PhoneNumber' => '09051149242',
            'Email' => 'vic+testlib+01@gmail.com'
        );

        $order = [
            'contactId' => $contactId,
            'description' => 'Order Description',
            'orderDate' => new \DateTime('now'),
            'orderType' => 'Online'
        ];

        $orderItems = [
            'productId' => 1,
            'itemType' => 4,
            'price' => 0.1,
            'quantity' => 1,
            'description' => "Desc: Test Order - By OOTB",
            'notes' => "Notes: Test Order - By OOTB"
        ];

        $invoice = [
            'invoiceId' => 987,
            'invoiceNotes' => 'Invoice Notes',
            'cardId' => 373,
            'merchantAccountId' => env('MERCHANT_ID'),
            'bypassCommissions' => false

        ];

        $manualPayment = [
            'invoiceId' => 909, 
            'amount' => 0.1, 
            'PayDate' => new \DateTime('now'),
            'PayType' => "Credit Card", 
            'invoiceNotes' => 'Invoice Notes', 
            'bypassCommissions' => false
        ];

        echo "<pre>";

        // echo '$this->api->deDupeContact(null, $contact)';
        // $items['deDupeContact'] = $this->api->deDupeContact(null, $contact);
        // echo "<br>";

        // echo '$this->api->fetchContactById($contactId)';
        // $items['fetchContactById'] = $this->api->fetchContactById($items['deDupeContact']);
        // echo "<br>";

        // echo '$this->api->deDupeCard($cardId, $card)';
        // $items['deDupeCard'] = $this->api->deDupeCard(null, $card);
        // echo "<br>";

        // echo '$this->api->fetchCard($items[deDupeCard])';
        // $items['fetchCard'] = $this->api->fetchCard($items['deDupeCard']);
        // echo "<br>";

        // echo '$this->api->blankOrder($order...)';
        // $items['blankOrder'] = $this->api->blankOrder(
        //     $order['contactId'],
        //     $order['description'],
        //     $order['orderDate'],
        //     $order['orderType']
        // );
        // echo "<br>";

        // echo '$this->api->addOrderItem($orderItems...)';
        // $items['addOrderItem'] = $this->api->addOrderItem(
        //     $items['blankOrder'],
        //     $orderItems['productId'],
        //     $orderItems['itemType'],
        //     $orderItems['price'],
        //     $orderItems['quantity'],
        //     $orderItems['description'],
        //     $orderItems['notes']
        // );
        // $items['addOrderItem'] = $this->api->addOrderItem(
        //     $items['blankOrder'],
        //     7,
        //     $orderItems['itemType'],
        //     $orderItems['price'],
        //     $orderItems['quantity'],
        //     $orderItems['description'],
        //     $orderItems['notes']
        // );
        // $items['addOrderItem'] = $this->api->addOrderItem(
        //     $items['blankOrder'],
        //     5,
        //     $orderItems['itemType'],
        //     $orderItems['price'],
        //     $orderItems['quantity'],
        //     $orderItems['description'],
        //     $orderItems['notes']
        // );
        // echo "<br>";
        // echo '$this->api->getOrderItems($items[\'blankOrder\'])';
        // $items['getOrderItems'] = $this->api->getOrderItems($items['blankOrder']);

        // // echo "<br>";
        // // echo '$this->api->chargeInvoice($invoice...)';
        // $invoice['cardId'] = $items['deDupeCard'];
        // $invoice['invoiceId'] = $items['blankOrder'];

        // $items['chargeInvoice'] = $this->api->chargeInvoice(
        //     $invoice['invoiceId'],
        //     $invoice['invoiceNotes'],
        //     $invoice['cardId'],
        //     $invoice['merchantAccountId'],
        //     $invoice['bypassCommissions']
        // );

        // echo "<br>";
        // echo '$this->api->addManualPayment($addManualPayment...)';
        // $manualPayment['invoiceId'] = $items['blankOrder'];
        // $items['addManualPayment'] = $this->api->addManualPayment(
        //     (int)$manualPayment['invoiceId'],
        //     (float)$manualPayment['amount'],
        //     $manualPayment['PayDate'],
        //     (string)$manualPayment['PayType'],
        //     (string)$manualPayment['invoiceNotes'],
        //     (bool)$manualPayment['bypassCommissions']
        // );

        // echo "<br>";
        // echo '$this->api->grpAssign($contactId, 111)';
        // $items['grpAssign'] = $this->api->grpAssign($contactId, 111);

        // echo "<br>";
        // echo '$this->api->updateCustomFields($contactId, array(\'_ActiveWeekMenu\' => \'Test Menu\'))';
        // $items['updateCustomFields'] = $this->api->updateCustomFields($contactId, 
        //     array('_ActiveWeekMenu' => 'Test Menu')
        // );


        // echo "<br>";
        // echo '$this->api->queryTable(...)';
        // $items['queryTable'][] = $this->api->queryTable('DataFormField', array('Name' => 'ActiveWeekMenu'));
        // $items['queryTable'][] = $this->api->queryTable('DataFormField', array('Name' => 'DeliveryMenu'));
        // $items['queryTable'][] = $this->api->queryTable('DataFormField', array('Name' => 'PausedCancelledPlans'));
        // $items['queryTable'][] = $this->api->queryTable('DataFormField', array('Name' => 'ActiveWeekCutOffDate'));
        // $items['queryTable'][] = $this->api->queryTable('DataFormField', array('Name' => 'ActiveWeekCutOff'));
        // $items['queryTable'][] = $this->api->queryTable('DataFormField', array('Name' => 'NextDeliveryDate'));
        // $items['queryTable'][] = $this->api->queryTable('DataFormField', array('Name' => 'DeliveryDatePretty'));
        // $items['queryTable'][] = $this->api->queryTable('DataFormField', array('Name' => 'PausedTillDate'));
        // $items['queryTable'][] = $this->api->queryTable('DataFormField', array('Name' => 'ActiveWeekDeliveryDate'));
        // $items['queryTable'][] = $this->api->queryTable('DataFormField', array('Name' => 'ActiveWeekDeliveryDatePretty'));
        // $items['queryTable'][] = $this->api->queryTable('DataFormField', array('Name' => 'ActiveDeliveryAddress'));
        // $items['queryTable'][] = $this->api->queryTable('DataFormField', array('Name' => 'DeliveryAddress'));

        // $this->api->addTagToContact(111, [13]);

        $items['fetchContactByEmail'] = $this->api->fetchContactByEmail('vic+test1@gmail.com', array('Id','FirstName','LastName'));

        echo "<br>";
        print_r($items);
    }

    public function add_contact($data=array()){
        $data=array("Email"=>"sunil@fusedsoftware.com", "FirstName"=>"James", "LastName"=>"Sahu", "Title"=>"Mr");
        $id = $this->infs->deDupeContact(null, $data);
        echo "$id<br>";
        if($id > 0){
            $this->optIn();
        }
    }

    public function update_contact($id=286471, $data=array()){
        $data=array("Email"=>"sunil@fusedsoftware.com", "FirstName"=>"James", "LastName"=>"Sahu", "Title"=>"Mr");
        $id = $this->infs->deDupeContact($id, $data);
    }

    public function get_card($query=array(), $return=false){
        $return = $this->infs->fetchCard(null,null,1,null, null,2018);
        echo "<pre>"; print_r($return); echo "</pre>";
    }

    public function add_card(){
        $id = $this->infs->deDupeCard(null, $data=array("ContactId"=>"286471", "NameOnCard"=>"James R Jackson", "CardNumber"=>"4645790045598017", "ExpirationMonth"=>"05", "ExpirationYear"=>"2021", "CVV2"=>"189"));
        dd($id);
    }

    public function create_order(){
        $ContactId = 286471;//72775;
        $OrderId = $this->infs->blankOrder($ContactId, "Customer Order", date("Y-m-d H:i:s"));
//$OrderId=46776;
        #add order item in the order
        $ProductId=1;
        $ItemType = 4;  
        /* All types of ItemType
        1 => 'Shipping',
        2 => 'Tax',
        3 => 'Service & Misc',
        4 => 'Product',
        5 => 'Upsell Product',
        6 => 'Finance Charge',
        7 => 'Special',
        8 => 'Program',
        9 => 'Subscription Plan',
        10 => 'Special: Free Trial Days',
        12 => 'Special: Order Total',
        13 => 'Special: Category',
        14 => 'Special: Shipping',
        */
        $price = 1;
        $quantity = 1;
        $description = "Desc: Test Order - By OOTB";
        $notes = "Notes: Test Order - By OOTB";
        $this->infs->addOrderItem($OrderId, $ProductId, $ItemType, $price, $quantity, $description="", $notes="");
        
        #fetching credit card 
        $status = 3;// Change to 3 later, pass null if you don't want the credit card status is needed to be considered
        $credit_card = $this->infs->fetchCard(null, $ContactId, $status, null, null, null);

        if(count($credit_card) > 0){
            
            #fetch invoice mapping to the order id created.
            $InvoiceId_res = $this->infs->queryTable("Invoice", array("JobId"=>$OrderId), array("Id"));
            $InvoiceId = "";

            if(count($InvoiceId_res) > 0){
                $InvoiceId = $InvoiceId_res[0]["Id"];

                $invoiceNotes = "Test Charges on the card.";
                $cardId = $credit_card[0]["Id"];
                $merchantAccountId=1;
                $bypassCommissions=false;

                $charge_result = $this->infs->chargeInvoice($InvoiceId, $invoiceNotes, $cardId, $merchantAccountId, $bypassCommissions);
                echo "<pre>Charge Result: "; print_r($charge_result); echo "</pre>";

                $amount=1;
                $PayDate=date("Y-m-d H:i:s"); $PayType="Cash"; $invoiceNotes="Test Pay"; 
//                $manual_result = $this->infs->addManualPayment($InvoiceId, $amount, $PayDate, $PayType, $invoiceNotes);
//                echo "<pre>Charge Result: "; print_r($manual_result); echo "</pre>";
            }
            
        }
        echo "<pre>"; print_r($credit_card); echo "</pre>";
        echo "Order Id: ".$OrderId;
    }

    public function optIn($email="", $reason=""){
        if($email == "") $email="sunil@fusedsoftware.com";
        if($reason == "") $reason="Opting in for testing";
        $return = $this->infs->optIn($email, $reason);
        dd($return);
    }

    public function optOut($email="", $reason=""){
        if($email == "") $email="sunil@fusedsoftware.com";
        if($reason == "") $reason="Opting out for testing";
        $return = $this->infs->optOut($email, $reason);
        dd($return);
    }

    public function getOptStatus($email=""){
        if($email == "") $email="sunil@fusedsoftware.com";
        $return = $this->infs->getOptStatus($email);
        dd($return);
    }

    public function manage_group($tag_name="", $tag_cat=""){
        if($tag_cat == "") $tag_cat="OOTB_TEST";
        if($tag_name == "") $tag_name="OOTB_TEST_Contact";
        $return_cat_id = $this->infs->deDupeTagCategory($tag_cat);
        $return_tag_id = $this->infs->deDupeTag($tag_name, $return_cat_id);
        var_dump($return_cat_id, $return_tag_id);
        return $return_tag_id;
    }

    public function assign_group(){
//        $return_tag_id = $this->manage_group();

        #assign tag 
        $ContactId = 5; $return_tag_id = 133;
        $result = $this->infs->grpAssign($ContactId, $return_tag_id);
        dd($return_tag_id, $result);
    }

    public function remove_group(){
//        $return_tag_id = $this->manage_group();
        #remove tag 
        $ContactId = 5; $return_tag_id = 133;
        $result = $this->_ActiveLocation->grpRemove($ContactId, $return_tag_id);
        dd($return_tag_id, $result);
    }

    public function checkStatus(){
        $app = app();
        $this->cust = new Customer(new \App\Repository\CustomerRepository);
       $result = $this->cust->checkStatus(Request::get("user_id"));
        // $result = $this->cust->updateCustomFields(5, array("_ActiveLocation"=>"Melbourne"));
        dd($result);
    }
    
    public function updateCustomField()
    {   

        $date = new \DateTime(date('Y-m-d'));
        $customField = (new \CustomFields);
        // \App\Jobs\INFSCustomFieldUpdate::dispatch(1, [
        //     $customField->getPausedCancelledPlans() => '7 Days Dinners',
        //     $customField->getPausedTillDate() => $date
        // ]);

        // (new \CustomFieldUpdate)->update(1, [
        //     $customField->getPausedCancelledPlans() => '7 Days Dinners',
        //     $customField->getPausedTillDate() => $date->format('d-m-Y')
        // ]);

        $app = app();
        $this->cust = new Customer(new \App\Repository\CustomerRepository);
//        $result = $this->cust->checkStatus(Request::get("user_id"));
        $result = $this->infs->processCustomFieldWithData("Contact", "_ActiveLocation", array("Melbourne", "Victoria"));
        dd($result);
    }
}
