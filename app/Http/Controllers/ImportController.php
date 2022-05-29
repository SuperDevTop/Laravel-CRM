<?php

namespace App\Http\Controllers;
use App\Models\variables\CustomerType;
use App\Models\variables\PayMethod;
use App\Models\variables\PaymentTerm;
use App\Models\variables\AdType;
use App\Models\variables\CustomerCreditRating;
use App\Models\User;
use App\Models\Customer;
use App\Models\Address;
use App\Models\VATConfirm;
use App\Models\Supplier;
use App\Models\Currency;
use Illuminate\Support\Facades\Schema;


use Illuminate\Support\Facades\DB;

class ImportController extends BaseController {

	public function import() {

        ini_set('memory_limit', '16G');

        CustomerType::where('id', '>', 1)->delete();
        PayMethod::where('id', '>', 4)->delete();
        User::where('id', '>', 1)->delete();
        PaymentTerm::where('id', '>', 1)->delete();
        AdType::where('id', '>', 1)->delete();
        CustomerCreditRating::where('id', '>', 1)->delete();
        Customer::where('id', '>', 1)->delete();
        Address::truncate();

        // New ID's
        $newIds = [
            "CustomerType" => [],
            "PaymentType" => [],
            "PaymentTerm" => [],
            'User' => [],
            'AdType' => [],
            'PaymentTerm' => [],
            'CreditRating' => [],
            'Address' => [],
            'Customer' => []
        ];

        //     _____            _             _     _______                    
        //    / ____|          | |           | |   |__   __|                   
        //   | |     ___  _ __ | |_ __ _  ___| |_     | |_   _ _ __   ___  ___ 
        //   | |    / _ \| '_ \| __/ _` |/ __| __|    | | | | | '_ \ / _ \/ __|
        //   | |___| (_) | | | | || (_| | (__| |_     | | |_| | |_) |  __/\__ \
        //    \_____\___/|_| |_|\__\__,_|\___|\__|    |_|\__, | .__/ \___||___/
        //                                                __/ | |              
        //                                               |___/|_|      
        $contactTypes = DB::connection('gwd-import')->select("SELECT * FROM `Contact Types`");

        foreach($contactTypes as $iCustomerType) {
            $customerType = new CustomerType;
            $customerType->type = $iCustomerType->{'ContactType'};
            $customerType->save();

            $newIds['CustomerType'][$customerType->type] = $customerType->id;
        }

        //    _____                                 _     _______                    
        //   |  __ \                               | |   |__   __|                   
        //   | |__) |_ _ _   _ _ __ ___   ___ _ __ | |_     | |_   _ _ __   ___  ___ 
        //   |  ___/ _` | | | | '_ ` _ \ / _ \ '_ \| __|    | | | | | '_ \ / _ \/ __|
        //   | |  | (_| | |_| | | | | | |  __/ | | | |_     | | |_| | |_) |  __/\__ \
        //   |_|   \__,_|\__, |_| |_| |_|\___|_| |_|\__|    |_|\__, | .__/ \___||___/
        //                __/ |                                 __/ | |              
        //               |___/                                 |___/|_|              
        $paymentTypes = DB::connection('gwd-import')->select("SELECT DISTINCT(Paymenttype) FROM Contacts");

        foreach(PayMethod::all() as $payMethod) {
            $newIds["PaymentType"][$payMethod->type] = $payMethod->id;
        }

        foreach($paymentTypes as $iPaymentType) {

            // If it already exists, continue
            if ($iPaymentType->{'Paymenttype'} == '' || PayMethod::where('type', '=', $iPaymentType->{'Paymenttype'})->count() > 0)
                continue;

            // Create the new one
            $paymentMethod = new PayMethod;
            $paymentMethod->type = $iPaymentType->{'Paymenttype'};
            $paymentMethod->commission = 0;
            $paymentMethod->save();

            $newIds["PaymentType"][$iPaymentType->{'Paymenttype'}] = $paymentMethod->id;
        }

        //    _    _                   
        //   | |  | |                  
        //   | |  | |___  ___ _ __ ___ 
        //   | |  | / __|/ _ \ '__/ __|
        //   | |__| \__ \  __/ |  \__ \
        //    \____/|___/\___|_|  |___/
        //                             
        $reps = DB::connection('gwd-import')->select("SELECT DISTINCT(rep) FROM Contacts");
        foreach($reps as $rep) {

            if ($rep->rep == '')
                continue;

            $user = new User;
            $user->username = $rep->{'rep'};
            $user->companyRole = 1;
            $user->userGroup = 1;
            $user->save();

            $newIds['User'][$user->username] = $user->id;
        }
        
        //    _____                                 _     _______                      
        //   |  __ \                               | |   |__   __|                     
        //   | |__) |_ _ _   _ _ __ ___   ___ _ __ | |_     | | ___ _ __ _ __ ___  ___ 
        //   |  ___/ _` | | | | '_ ` _ \ / _ \ '_ \| __|    | |/ _ \ '__| '_ ` _ \/ __|
        //   | |  | (_| | |_| | | | | | |  __/ | | | |_     | |  __/ |  | | | | | \__ \
        //   |_|   \__,_|\__, |_| |_| |_|\___|_| |_|\__|    |_|\___|_|  |_| |_| |_|___/
        //                __/ |                                                        
        //               |___/                                                         
        $terms = DB::connection('gwd-import')->select('SELECT DISTINCT(Paymentterms) FROM Contacts');
        foreach($terms as $term) {

            if ($term->Paymentterms == '')
                continue;

            $paymentTerm = new PaymentTerm;
            $paymentTerm->type = $term->Paymentterms;
            $paymentTerm->save();

            $newIds['PaymentTerm'][$paymentTerm->type] = $paymentTerm->id;
        }

        //                _ _______               
        //       /\      | |__   __|              
        //      /  \   __| |  | |_   _ _ __   ___ 
        //     / /\ \ / _` |  | | | | | '_ \ / _ \
        //    / ____ \ (_| |  | | |_| | |_) |  __/
        //   /_/    \_\__,_|  |_|\__, | .__/ \___|
        //                        __/ | |         
        //                       |___/|_|         
        $types = DB::connection('gwd-import')->select('SELECT DISTINCT(ReferredBy) FROM Contacts');
        foreach($types as $type) {

            if ($type->ReferredBy == '')
                continue;

            $adType = new AdType;
            $adType->type = $type->ReferredBy;
            $adType->save();

            $newIds['AdType'][$adType->type] = $adType->id;
        }

        //     _____              _ _ _     _      _           _ _   
        //    / ____|            | (_) |   | |    (_)         (_) |  
        //   | |     _ __ ___  __| |_| |_  | |     _ _ __ ___  _| |_ 
        //   | |    | '__/ _ \/ _` | | __| | |    | | '_ ` _ \| | __|
        //   | |____| | |  __/ (_| | | |_  | |____| | | | | | | | |_ 
        //    \_____|_|  \___|\__,_|_|\__| |______|_|_| |_| |_|_|\__|
        //                                                           
        $limits = DB::connection('gwd-import')->select('SELECT DISTINCT(CreditLimit) FROM Contacts');
        foreach($limits as $limit) {

            if ($limit->CreditLimit == '')
                continue;

            $rating = new CustomerCreditRating;
            $rating->type = $limit->CreditLimit;
            $rating->save();

            $newIds['CreditRating'][$rating->type] = $rating->id;

        }

        //     _____          _                                
        //    / ____|        | |                               
        //   | |    _   _ ___| |_ ___  _ __ ___   ___ _ __ ___ 
        //   | |   | | | / __| __/ _ \| '_ ` _ \ / _ \ '__/ __|
        //   | |___| |_| \__ \ || (_) | | | | | |  __/ |  \__ \
        //    \_____\__,_|___/\__\___/|_| |_| |_|\___|_|  |___/
        //                                                     
        $customers = DB::connection('gwd-import')->select('SELECT * FROM Contacts');
        foreach($customers as $contact) {

            $links = [
                'shopName' => 'Shop name',
                'companyName' => 'CompanyName',
                'contactName' => 'ContactName',
                // FIRST / LAST NAME
                'phone' => 'WorkPhone',
                'mobile' => 'MobilePhone',
                'fax' => 'FaxNumber',
                'email' => 'EmailName', // Second email?
                'website' => 'ownwebsite',
                // DISCOUNTS ALLOWED (skip)
                'cifnif' => 'VATNº',
                'notes' => 'Notes',
                // TAX RATE
                'notes2' => 'Packing Notes',
                'newsletter' => 'Newsletter',
                'created_at' => 'Date Entered on Database',
                // INVOICE NOTES
                // CONTACT HISTORY?
                'skype' => 'Skype Address',
                'deliveryAddress' => 'IDDelivery',

                'address' => 'Address',
                'city' => 'towns',
                'postalCode' => 'PostalCode',
                'country' => 'Country'
            ];

            $contact->ContactName = $contact->FirstName . ' ' . $contact->LastName;

            // Contact name (fn + ln

            $customer = new Customer;
            foreach($links as $pepper => $gwd) {
                if ($pepper == 'created_at')
                    continue;

                if ($contact->{$gwd} === null)
                    $contact->{$gwd} = '';

                $customer->{$pepper} = $contact->{$gwd};
            }

            $currencies = [
                '£' => 3,
                '€' => 1,
                'Euro' => 1,
                '€Jensusczeck@aol.€' => 1,
                '' => 1
            ];

            $customer->type = (array_key_exists($contact->ContactTypeID, $newIds['CustomerType'])) ? $newIds['CustomerType'][$contact->ContactTypeID] : 1;
            $customer->paymentType = (array_key_exists($contact->Paymenttype, $newIds['PaymentType'])) ? $newIds['PaymentType'][$contact->Paymenttype] : 1;
            $customer->managedBy = (array_key_exists($contact->rep, $newIds['User'])) ? $newIds['User'][$contact->rep] : 1;
            $customer->advertisingType = (array_key_exists($contact->ReferredBy, $newIds['AdType'])) ? $newIds['AdType'][$contact->ReferredBy] : 1;
            $customer->paymentTerms = (array_key_exists($contact->Paymentterms, $newIds['PaymentTerm'])) ? $newIds['PaymentTerm'][$contact->Paymentterms] : 1;
            $customer->currency = $currencies[$contact->Currency];
            $customer->credit = ($contact->CreditLimit != 0) ? $newIds['CreditRating'][$contact->CreditLimit] : 1;

            $customer->save();

            // VAT CONFIRM
            if ($contact->{'VAT Confirm'} != '' || $contact->{'VAT Confirm date'} != '' || $contact->{'VAT Proof'} != '') {
                $confirm = new VATConfirm;
                $confirm->customer = $customer->id;
                $confirm->user = 1;
                $confirm->text = $contact->{'VAT Proof'};
                $confirm->created_at = $contact->{'VAT Confirm date'};
                $confirm->save();
            }

            $newIds['Customer'][$contact->ContactID] = $customer->id;

        }


        //                _     _                             
        //       /\      | |   | |                            
        //      /  \   __| | __| |_ __ ___  ___ ___  ___  ___ 
        //     / /\ \ / _` |/ _` | '__/ _ \/ __/ __|/ _ \/ __|
        //    / ____ \ (_| | (_| | | |  __/\__ \__ \  __/\__ \
        //   /_/    \_\__,_|\__,_|_|  \___||___/___/\___||___/
        //                                                    
        $addresses = DB::connection('gwd-import')->select("SELECT * FROM deliveryaddress");
        foreach($addresses as $iAddress) {

            if (!array_key_exists($iAddress->contactID, $newIds['Customer']))
                continue;

            $address = new Address;
            $address->customer = $newIds['Customer'][$iAddress->contactID];
            $address->label = $iAddress->Name;

            if ($address->label == '')
                $address->label = $iAddress->Company;

            $address->address = $iAddress->Address . PHP_EOL . $iAddress->{'Address 2'};
            $address->city = $iAddress->Town;
            $address->postalcode = $iAddress->Postcode;
            $address->country = $iAddress->Country;
            $address->telephone = $iAddress->Tel1;
            // TODO: Tel2?
            $address->email = $iAddress->email;
            // TODO: Mob
            // TODO: FAX
            // TODO: Newsletter branches?
            // TODO: Compny Name Contacts
            // TODO: Branch Manager?
            // TODO: Mailshot special
            // TODO: Notes
            $address->save();

            $newIds['Address'][$iAddress->IDDelivery] = $address->id;
        }
        foreach(Customer::all() as $customer) {
            if (!$customer->deliveryAddress)
                continue;

            $customer->deliveryAddress = $newIds['Address'][$customer->deliveryAddress];
            $customer->save();
        }

        //     _____                   _ _               
        //    / ____|                 | (_)              
        //   | (___  _   _ _ __  _ __ | |_  ___ _ __ ___ 
        //    \___ \| | | | '_ \| '_ \| | |/ _ \ '__/ __|
        //    ____) | |_| | |_) | |_) | | |  __/ |  \__ \
        //   |_____/ \__,_| .__/| .__/|_|_|\___|_|  |___/
        //                | |   | |                      
        //                |_|   |_|                      
        $suppliers = DB::connection('gwd-import')->select('SELECT * FROM Suppliers');
        foreach($suppliers as $iSupplier) {

            $supplier = new Supplier;

            $currencies = [
                '£' => 3,
                '€' => 1,
                '' => 1
            ];

            $links = [
                'companyName' => 'Supplier',
                'contactName' => 'Contact Name',
                'address' => 'street',
                'city' => 'Town',
                'postalCode' => 'Postcode',
                'country' => 'Country',
                'phone' => 'Tel 1',
                // TODO: Tel2
                'mobile' => 'Mobile',
                'email' => 'email 1',
                // TODO: email 2,
                'fax' => 'Fax',
                'cifnif' => 'CIF',
                'notes' => 'Notes'
            ];

            foreach($links as $pepper => $gwd) {

                if ($iSupplier->{$gwd} == null)
                    $iSupplier->{$gwd} = '';

                $supplier->{$pepper} = $iSupplier->{$gwd};
            }

            $supplier->paymentTerms = (array_key_exists($iSupplier->Paymentterms, $newIds['PaymentTerm'])) ? $newIds['PaymentTerm'][$iSupplier->Paymentterms] : 1;
            $supplier->paymentType = (array_key_exists($iSupplier->Paymenttype, $newIds['PaymentType'])) ? $newIds['PaymentType'][$iSupplier->Paymenttype] : 1;
            $supplier->currency = $currencies[$iSupplier->Currency];
            // TODO: Categories

            $supplier->save();

        }
	}

    public function update() {

        Schema::create('paymentterms', function($table) {
            $table->increments('id');
            $table->string('type');
            $table->timestamps();
        });

        $term = new PaymentTerm;
        $term->type = "Not Applicable";
        $term->save();

        Schema::create('currencies', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('symbol');
            $table->string('abbreviation');
            $table->timestamps();
        });

        $cur1 = new Currency;
        $cur1->name = "Euro";
        $cur1->symbol = "€";
        $cur1->abbreviation = "EUR";
        $cur1->save();

        $cur2 = new Currency;
        $cur2->name = "U.S. Dollar";
        $cur2->symbol = "$";
        $cur2->abbreviation = "USD";
        $cur2->save();

        $cur3 = new Currency;
        $cur3->name = "Pound Sterling";
        $cur3->symbol = "£";
        $cur3->abbreviation = "GBP";
        $cur3->save();

        Schema::create('customertypes', function($table) {
            $table->increments('id');
            $table->string('type');
            $table->timestamps();
        });

        $type = new CustomerType;
        $type->type = "Not Applicable";
        $type->save();

        Schema::create('addresses', function($table) {
            $table->increments('id');
            $table->integer('customer');
            $table->string('label');
            $table->string('address');
            $table->string('city');
            $table->string('postalcode');
            $table->string('province');
            $table->string('country');
            $table->string('telephone');
            $table->string('email');
            $table->timestamps();
        });

        Schema::create('vatconfirms', function($table) {
            $table->increments('id');
            $table->integer('customer');
            $table->integer('user');
            $table->string('text');
            $table->timestamps();
        });

        /////////////////////////////////////////////////////////

        Schema::table('customers', function($table) {
            $table->string('shopName');
            $table->integer('paymentTerms');
            $table->integer('currency');
            $table->string('skype');
            $table->integer('type');
            $table->integer('deliveryAddress');
        });

        Schema::table('suppliers', function($table) {
            $table->integer('paymentTerms');
            $table->integer('paymentType');
            $table->integer('currency');
        });

        $managedBy = User::orderBy('id', 'ASC')->first()->id;

        foreach(Customer::all() as $customer) {
            $customer->managedBy = $managedBy;
            $customer->paymentTerms = 1;
            $customer->currency = 1;
            $customer->type = 1;
            $customer->save();
        }

        $payMethod = PayMethod::orderBy('id', 'ASC')->first()->id;

        foreach(Supplier::all() as $supplier) {
            $supplier->paymentTerms = 1;
            $supplier->paymentType = $payMethod;
            $supplier->currency = 1;
        }

        return 'YES';
    }
}

?>