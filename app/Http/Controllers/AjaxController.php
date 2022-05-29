<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

use App\Models\User;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Customer;
use App\Models\Settings;
use App\Model\QuoteDetail;
use App\Models\variables\CustomerCreditRating;

use App\Classes\CommonFunctions;
use App\Classes\LogManager;

class AjaxController extends Controller {
    // Delete a user (if it exists), and redirect back to the user index page
    public function getDeleteUser(Request $request) {
        $userId = (int) $request->get('id');
        User::find($userId)->delete();
        return redirect('users.index')
        ->with('flash_msg', 'User deleted');
    }
    
    public function ajaxRequest($type) {
        $returnArray = array();
        switch($type) {
            case 'delete_customer':
                if (!Auth::user()->hasPermission('customer_delete'))
                    return;
                    
                    $customer = Customer::find(Request::get('customerId'));
                    $returnArray['success'] = false;
                    $returnArray['msg'] = 'This feature is work-in-progress';
                    // Log change
                    /*LogManager::log('Deleted customer: ' . $customer->getCustomerName());
                     $returnArray['msg'] = 'Customer succesfully deleted.';
                     $customer->delete();
                     $returnArray['success'] = true;
                     $returnArray['refresh'] = true; // Refresh the page*/
                    break;
            case 'contacthistory_newMessage':
                if (!Auth::user()->hasPermission('customer_history'))
                    return;
                    $contactItem = new ContactHistory;
                    $contactItem->placedOn = CommonFunctions::getMysqlDate();
                    $contactItem->placedBy = Auth::user()->id;
                    $contactItem->customer = Request::get('customerId');
                    $contactItem->message = Request::get('message');
                    $contactItem->save();
                    
                    $returnArray['success'] = true;
                    $returnArray['msg'] = 'Contact message saved succesfully.';
                    $returnArray['refresh'] = false;
                    $returnArray['new_row'] = '<tr><td>' . CommonFunctions::formatDatetime("now") . '</td><td>' . Auth::user()->getFullname() . '</td><td>' . Request::get('message') . '</td></tr>';
                    break;
                    
            case 'supplier_contacthistory_newMessage':
                $contactItem = new SupplierComment;
                $contactItem->placedOn = CommonFunctions::getMysqlDate();
                $contactItem->placedBy = Auth::user()->id;
                $contactItem->supplier = Request::get('supplierId');
                $contactItem->message = Request::get('message');
                $contactItem->save();
                
                $returnArray['success'] = true;
                $returnArray['msg'] = 'Contact message saved succesfully.';
                $returnArray['refresh'] = false;
                $returnArray['new_row'] = '<tr><td>' . CommonFunctions::formatDatetime("now") . '</td><td>' . Auth::user()->getFullname() . '</td><td>' . Request::get('message') . '</td></tr>';
                break;
            case 'get_product_price':
                $product = Product::find(Request::get('productId'));
                $returnArray['success'] = true;
                $returnArray['productPrice'] = $product->salesPrice;
                break;
            case 'product_remove':
                if (!Auth::user()->hasPermission('product_delete'))
                    return;
                    
                    $product = Product::find(Request::get('productId'));
                    
                    if (!$product)
                        return;
                        
                        // Check if the product is used somewhere
                        $usedCount = QuoteDetail::where('productId', '=', $product->id)->count();
                        $usedCount += Renewal::where('product', '=', $product->id)->count();
                        
                        if ($usedCount == 0) {
                            $product->delete();
                            return array('success' =>true);
                        } else
                            return array('success' => false, 'quoteCount' => $usedCount);
                            break;
            case 'product_multi_remove':
                if (!Auth::user()->hasPermission('product_delete'))
                    return;
                    
                    $productsRemoved = 0;
                    $productsNotRemoved = 0;
                    foreach(get('productIds') as $productId) {
                        $product = Product::find($productId);
                        
                        if (!$product)
                            return;
                            
                            // Check if the product is used somewhere
                            $usedCount = QuoteDetail::where('productId', '=', $product->id)->count();
                            
                            if ($usedCount == 0) {
                                $product->delete();
                                $productsRemoved++;
                            } else {
                                $productsNotRemoved++;
                            }
                    }
                    
                    return ['success' => true, 'removed' => $productsRemoved, 'not_removed' => $productsNotRemoved];
                    break;
            case 'product_archive':
                if (!Auth::user()->hasPermission('product_delete'))
                    return;
                    
                    $product = Product::find(Request::get('productId'));
                    
                    if (!$product || $product->discontinued == 1)
                        return;
                        
                        $product->discontinued = 1;
                        $product->save();
                        
                        return ['success' => true];
                        break;
            case 'product_multi_archive':
                if (!Auth::user()->hasPermission('product_delete'))
                    return;
                    
                    foreach(Request::get('productIds') as $productId) {
                        $product = Product::find($productId);
                        
                        if (!$product || $product->discontinued == 1)
                            return;
                            
                            $product->discontinued = 1;
                            $product->save();
                    }
                    
                    return ['success' => true];
                    break;
            case 'get_quote':
                // Check if the quote exists
                $quote = Quote::find(Request::get('quoteId'));
                if (!$quote) return;
                
                $returnArray['quoteData'] = $quote->toArray();
                $returnArray['quoteData']['createdOn'] = CommonFunctions::formatDateTime($returnArray['quoteData']['createdOn']);
                $returnArray['quoteData']['startedOn'] = CommonFunctions::formatDateTime($returnArray['quoteData']['startedOn']);
                $returnArray['quoteData']['requiredBy'] = CommonFunctions::formatDateTime($returnArray['quoteData']['requiredBy']);
                $returnArray['quoteData']['completedOn'] = CommonFunctions::formatDateTime($returnArray['quoteData']['completedOn']);
                
                $returnArray['entries'] = $quote->getEntries->toArray();
                
                $returnArray['employees'] = CommonFunctions::getDropdownData('employees', $quote->assignedTo);
                
                $returnArray['adTypes'] = AdType::where('discontinued', '=', 0)->select('id', 'type')->get();
                
                $returnArray['jobStatuses'] = JobStatus::orderBy('type', 'ASC')->select('id', 'type')->get();
                
                $returnArray['vats'] = VAT::pluck('type', 'id');
                
                $returnArray['vatValues'] = VAT::pluck('value', 'id');
                
                $returnArray['comments'] = $quote->getComments->toArray();
                
                $returnArray['customer'] = Customer::find($quote->customer)->toArray();
                
                $returnArray['emailTemplate'] = Settings::setting('quoteEmailTemplate');
                
                // Get an array of all the current products in the quote. This will get used by the product dropdowns
                $productSelections = [];
                foreach($quote->getEntries as $entry) {
                    $productSelections[] = [
                        'id' => $entry->productId,
                        'name' => $entry->productName
                    ];
                }
                $returnArray['productSelections'] = $productSelections;
                
                $returnArray['success'] = true;
                $returnArray['refresh'] = false;
                break;
            case 'save_quote':
                $quote = Quote::find(Request::get('quoteId'));
                if (!$quote) {
                    return;
                }
                
                $idsDone = [];
                
                $entries = (Request::get('entries')) ? Request::get('entries') : array();
                foreach($entries as $entry) {
                    unset($entry['$$hashKey']);
                    
                    $quoteEntry = QuoteDetail::find($entry['id']);
                    if ($quoteEntry) {
                        $quoteEntry->fill($entry);
                        $quoteEntry->productName = Product::find($entry['productId'])->name;
                        
                        if (array_key_exists('visitDate', $entry))
                            $quoteEntry->visitDate = CommonFunctions::parseMaskedDateTime($entry['visitDate']);
                            
                            $quoteEntry->save();
                            array_push($idsDone, $quoteEntry->id);
                    }
                    if ($entry['id'] == -1) {
                        // New entry, add to db
                        $newEntry = new QuoteDetail;
                        $newEntry->fill($entry);
                        $newEntry->quoteId = Request::get('quoteId');
                        $newEntry->productName = Product::find($entry['productId'])->name;
                        
                        if (array_key_exists('visitDate', $entry))
                            $newEntry->visitDate = CommonFunctions::parseMaskedDateTime($entry['visitDate']);
                            
                            $newEntry->save();
                            array_push($idsDone, $newEntry->id);
                    }
                }
                
                // Delete deleted entries
                if (empty($idsDone)) {
                    $idsDone[] = -9999;
                }
                QuoteDetail::where('quoteId', '=', $quote->id)->whereNotIn('id', $idsDone)->delete();
                
                $quoteData = Request::get('quoteData');
                unset($quoteData['total']);
                unset($quoteData['subtotal']);
                unset($quoteData['customerName']);
                unset($quoteData['statusText']);
                
                // If assignedTo and/or status has chanegd, insert comment
                if ($quoteData['status'] != $quote->status) {
                    $quote->addComment('Status changed from ' . $quote->getStatus->type . ' to ' . JobStatus::find($quoteData['status'])->type);
                }
                if ($quoteData['assignedTo'] != $quote->assignedTo) {
                    $quote->addComment('Assigned to ' . User::find($quoteData['assignedTo'])->getFullname());
                }
                
                $quote->fill($quoteData);
                $quote->createdOn = CommonFunctions::parseMaskedDateTime(Request::get('quoteData')['createdOn']);
                $quote->startedOn = CommonFunctions::parseMaskedDateTime(Request::get('quoteData')['startedOn']);
                $quote->requiredBy = CommonFunctions::parseMaskedDateTime(Request::get('quoteData')['requiredBy']);
                $quote->completedOn = CommonFunctions::parseMaskedDateTime(Request::get('quoteData')['completedOn']);
                $quote->save();
                break;
            case 'delete_quote_entry':
                QuoteDetail::where('quoteId', '=', Request::get('quoteId'))->where('id', '=', Request::get('entryId'))->delete();
                break;
            case 'save_quote_information':
                $quote = Quote::find(Request::get('quoteId'));
                if ($quote) {
                    $quote->fill(Requestexcept('quoteId', 'customer', 'createdBy'));
                    $quote->requiredBy = CommonFunctions::dateTimePickerToMysqlDateTime(Request::get('requiredBy'));
                    $quote->completedOn = CommonFunctions::dateTimePickerToMysqlDateTime(Request::get('completedOn'));
                    $quote->startedOn = CommonFunctions::dateTimePickerToMysqlDateTime(Request::get('startedOn'));
                    $quote->save();
                }
                break;
            case 'quote_new_comment':
                $quoteComment = new QuoteComment;
                $quoteComment->quoteId = Request::get('quoteId');
                $quoteComment->placedBy = Auth::user()->id;
                $quoteComment->placedOn = CommonFunctions::getMysqlDate();
                
                $comment = ucfirst(Request::get('comment'));
                $comment = preg_replace_callback('/[.!?].*?\w/', create_function('$matches', 'return strtoupper($matches[0]);'),$comment);
                
                $quoteComment->comment = $comment;
                $quoteComment->save();
                
                return array('success' => true, 'commentId' => $quoteComment->id);
                break;
                
            case 'invoice_new_comment':
                $invoiceComment = new InvoiceComment;
                $invoiceComment->invoiceId = Request::get('invoiceId');
                $invoiceComment->placedBy = Auth::user()->id;
                $invoiceComment->placedOn = CommonFunctions::getMysqlDate();
                
                $comment = ucfirst(Request::get('comment'));
                $comment = preg_replace_callback('/[.!?].*?\w/', create_function('$matches', 'return strtoupper($matches[0]);'),$comment);
                
                $invoiceComment->comment = $comment;
                $invoiceComment->save();
                
                return array('success' => true, 'commentId' => $invoiceComment->id);
                break;
                
            case 'creditnote_new_comment':
                $creditnoteComment = new CreditnoteComment;
                $creditnoteComment->creditnoteId = Request::get('creditnoteId');
                $creditnoteComment->placedBy = Auth::user()->id;
                $creditnoteComment->placedOn = CommonFunctions::getMysqlDate();
                
                $comment = ucfirst(Request::get('comment'));
                $comment = preg_replace_callback('/[.!?].*?\w/', create_function('$matches', 'return strtoupper($matches[0]);'),$comment);
                
                $creditnoteComment->comment = $comment;
                $creditnoteComment->save();
                
                return array('success' => true, 'commentId' => $creditnoteComment->id);
                break;
                
            case 'duplicate_quote':
                $quote = Quote::find(Request::get('quoteId'));
                
                if (!$quote)
                    return ['success' => false];
                    
                    $newQuote = new Quote;
                    $newQuote->createdOn = CommonFunctions::getMysqlDate();
                    $newQuote->customer = $quote->customer;
                    $newQuote->description = $quote->description;
                    $newQuote->status = $quote->status;
                    $newQuote->createdBy = Auth::id();
                    $newQuote->assignedTo = Auth::id();
                    $newQuote->adType = AdType::where('type', 'LIKE', '%existing customer%')->first()->id;
                    $newQuote->vat = $quote->vat;
                    $newQuote->supCosts = $quote->supCosts;
                    $newQuote->startedOn = CommonFunctions::getMysqlDate();
                    $newQuote->save();
                    
                    foreach($quote->getEntries as $entry) {
                        $newEntry = new QuoteDetail;
                        $newEntry->quoteId = $newQuote->id;
                        $newEntry->productId = $entry->productId;
                        $newEntry->productName = $entry->productName;
                        $newEntry->purchasePrice = $entry->productPrice;
                        $newEntry->unitPrice = $entry->unitPrice;
                        $newEntry->quantity = $entry->quantity;
                        $newEntry->discount = $entry->discount;
                        $newEntry->visitDate = $entry->visit;
                        $newEntry->finishDate = $entry->finishDate;
                        $newEntry->description = $entry->description;
                        $newEntry->save();
                    }
                    
                    return ['success' => true, 'quoteId' => $newQuote->id];
                    break;
                    
            case 'set_selected_quote_template':
                $system = System::find(1);
                $system->quoteTemplate = Request::get('templateId');
                $system->save();
                break;
            case 'save_template_settings':
                $system = System::find(1);
                $system->fill(Requestonly('templateColor', 'quoteText', 'invoiceText'));
                $system->save();
                break;
            case 'get_user_group_permissions':
                $userGroup = UserGroup::find(Request::get('groupId'));
                
                if ($userGroup && $userGroup->id != 0) {
                    $userPerms = array();
                    $groupPermissions = $userGroup->getPermissions();
                    
                    foreach($groupPermissions as $perm) {
                        $userPerms[$perm] = true;
                    }
                    
                    $returnArray['permissions'] = $userPerms;
                }
                break;
            case 'save_user_group_permissions':
                $userGroup = UserGroup::find(Request::get('groupId'));
                
                if ($userGroup && $userGroup->id != 0) {
                    UserGroupPermission::where('groupId', '=', $userGroup->id)->delete();
                    
                    foreach(Request::get('permissions') as $permission => $has) {
                        if ($has == 'true') {
                            $groupPermission = new UserGroupPermission;
                            $groupPermission->groupId = $userGroup->id;
                            $groupPermission->permission = $permission;
                            $groupPermission->save();
                        }
                    }
                }
                break;
            case 'delete_user_group':
                $userGroup = UserGroup::find(Request::get('groupId'));
                if ($userGroup && $userGroup->id != 0) {
                    if ($userGroup->getMemberCount() == 0) {
                        UserGroupPermission::where('groupId', '=', $userGroup->id)->delete();
                        $userGroup->delete();
                        $returnArray['success'] = true;
                    } else {
                        $returnArray['success'] = false;
                    }
                }
                break;
            case 'get_invoice':
                $invoice = Invoice::find(Request::get('invoiceId'));
                if ($invoice) {
                    $returnArray = [];
                    $returnArray['invoiceData'] = $invoice->toArray();
                    $returnArray['vats'] = VAT::pluck('type', 'id');
                    $returnArray['vatValues'] = VAT::pluck('value', 'id');
                    
                    $returnArray['irpfs'] = VAT::pluck('type', 'id');
                    $returnArray['irpfValues'] = VAT::pluck('value', 'id');
                    
                    
                    $returnArray['entries'] = $invoice->getEntries->toArray();
                    $returnArray['defaultBankCharge'] = Settings::setting('defaultDirectDebitBankCharge');
                    
                    $returnArray['comments'] = $invoice->getComments->toArray();
                    
                    $returnArray['customer'] = Customer::find($invoice->customer)->toArray();
                    
                    $returnArray['emailTemplate'] = Settings::setting('invoiceEmailTemplate');
                    
                    $returnArray['$scope.invoicewithIrpf'] = Settings::setting('invoiceHasIrpf');
                    
                    return $returnArray;
                }
                break;
            case 'get_creditnote':
                $creditnote = Creditnote::find(Request::get('creditnoteId'));
                if ($creditnote) {
                    $returnArray = [];
                    $returnArray['creditnoteData'] = $creditnote->toArray();
                    $returnArray['vats'] = VAT::pluck('type', 'id');
                    $returnArray['vatValues'] = VAT::pluck('value', 'id');
                    
                    $returnArray['irpf'] = VAT::pluck('type', 'id');
                    $returnArray['irpfValues'] = VAT::pluck('value', 'id');
                    
                    
                    $returnArray['entries'] = $creditnote->getEntries->toArray();
                    $returnArray['defaultBankCharge'] = Settings::setting('defaultDirectDebitBankCharge');
                    
                    $returnArray['comments'] = $creditnote->getComments->toArray();
                    
                    $returnArray['customer'] = Customer::find($creditnote->customer)->toArray();
                    
                    $returnArray['emailTemplate'] = Settings::setting('invoiceEmailTemplate');
                    
                    return $returnArray;
                }
                break;
            case 'get_invoice_quote':
                $quote = Quote::find(Request::get('quoteId'));
                if ($quote) {
                    $returnArray['quote'] = $quote->toArray();
                    $returnArray['success'] = true;
                    return $returnArray;
                } else {
                    return array('success' => false, 'message' => 'Quote not found');
                }
                break;
            case 'save_invoice':
                $invoice = Invoice::find(Request::get('invoiceId'));
                
                if ($invoice) {
                    $invoiceData = Request::get('invoice');
                    unset($invoiceData['total']);
                    unset($invoiceData['customerName']);
                    $invoice->fill($invoiceData);
                    $invoice->save();
                    
                    $entries = (Request::get('entries')) ? Request::get('entries') : array();
                    $idsDone = [];
                    foreach($entries as $entry) {
                        unset($entry['$$hashKey']);
                        $invoiceEntry = InvoiceDetail::find($entry['id']);
                        if ($invoiceEntry) {
                            $invoiceEntry->fill($entry);
                            $invoiceEntry->save();
                            array_push($idsDone, $invoiceEntry->id);
                        }
                        if ($entry['id'] == -1) {
                            // New entry, add to db
                            $newEntry = new InvoiceDetail;
                            $newEntry->fill($entry);
                            $newEntry->invoiceId = Request::get('invoiceId');
                            $newEntry->save();
                            array_push($idsDone, $newEntry->id);
                        }
                    }
                    
                    // Delete deleted entries
                    if (empty($idsDone)) {
                        $idsDone[] = -9999;
                    }
                    InvoiceDetail::where('invoiceId', '=', $invoice->id)->whereNotIn('id', $idsDone)->delete();
                    
                    return array('success' => true, 'message' => 'Invoice saved succesfully');
                }
                break;
            case 'save_creditnote':
                $creditnote = Creditnote::find(Request::get('creditnoteId'));
                
                if ($creditnote) {
                    $creditnoteData = Request::get('creditnote');
                    unset($creditnoteData['total']);
                    unset($creditnoteData['customerName']);
                    $creditnote->fill($creditnoteData);
                    $creditnote->save();
                    
                    $entries = (Request::get('entries')) ? Request::get('entries') : array();
                    $idsDone = [];
                    foreach($entries as $entry) {
                        unset($entry['$$hashKey']);
                        $creditnoteEntry = CreditnoteDetail::find($entry['id']);
                        if ($creditnoteEntry) {
                            $creditnoteEntry->fill($entry);
                            $creditnoteEntry->save();
                            array_push($idsDone, $creditnoteEntry->id);
                        }
                        if ($entry['id'] == -1) {
                            // New entry, add to db
                            $newEntry = new CreditnoteDetail;
                            $newEntry->fill($entry);
                            $newEntry->creditnoteId = Request::get('creditnoteId');
                            $newEntry->save();
                            array_push($idsDone, $newEntry->id);
                        }
                    }
                    
                    // Delete deleted entries
                    if (empty($idsDone)) {
                        $idsDone[] = -9999;
                    }
                    CreditnoteDetail::where('creditnoteId', '=', $creditnote->id)->whereNotIn('id', $idsDone)->delete();
                    
                    return array('success' => true, 'message' => 'Credit note saved succesfully');
                }
                break;
            case 'create_new_quote_invoice':
                // Create a new invoice with the quote in it
                $quote = Quote::find(Request::get('quoteId'));
                
                if ($quote) {
                    $invoice = new Invoice;
                    $invoice->id = (Invoice::max('id') + 1);
                    $invoice->customer = $quote->customer;
                    $invoice->createdOn = date('Y-m-d H:i:s');
                    $invoice->jobTitle = $quote->description;
                    $invoice->createdBy = Auth::user()->id;
                    $invoice->vat = Settings::setting('defaultVat');
                    $invoice->vat_per = 0;
                    $invoice->irpf = 0;
                    $invoice->irpf_per = 0;
                    $invoice->subtotal = 0;
                    
                    $invoice->description = '';
                    $invoice->notes = '';
                    $invoice->save();
                    
                    $invoiceDetail = new InvoiceDetail;
                    $invoiceDetail->invoiceId = $invoice->id;
                    $invoiceDetail->productId = -999;
                    $invoiceDetail->productName = 'Quote #' . $quote->id;
                    $invoiceDetail->quoteId = $quote->id;
                    $invoiceDetail->unitPrice = $quote->getSubTotal();
                    $invoiceDetail->quantity = 1;
                    $invoiceDetail->discount = 0;
                    $invoiceDetail->supCosts = $quote->supCosts;
                    $invoiceDetail->description = $quote->description;
                    $invoiceDetail->save();
                    
                    return array('success' => true, 'invoiceId' => $invoice->id);
                } else {
                    return array('success' => false);
                }
                break;
                
            case 'create_new_quote_creditnote':
                // Create a new creditnote with the quote in it
                $quote = Quote::find(Request::get('quoteId'));
                
                if ($quote) {
                    $creditnote = new Creditnote;
                    $creditnote->id = (Creditnote::max('id') + 1);
                    $creditnote->customer = $quote->customer;
                    $creditnote->createdOn = date('Y-m-d H:i:s');
                    $creditnote->jobTitle = $quote->description;
                    $creditnote->createdBy = Auth::user()->id;
                    $creditnote->vat = Settings::setting('defaultVat');
                    $creditnote->description = '';
                    $creditnote->notes = '';
                    $creditnote->save();
                    
                    $creditnoteDetail = new CreditnoteDetail;
                    $creditnoteDetail->creditnoteId = $creditnote->id;
                    $creditnoteDetail->productId = -999;
                    $creditnoteDetail->productName = 'Quote #' . $quote->id;
                    $creditnoteDetail->quoteId = $quote->id;
                    $creditnoteDetail->unitPrice = ($quote->getSubTotal() * (-1));
                    $creditnoteDetail->quantity = -1;
                    $creditnoteDetail->discount = 0;
                    $creditnoteDetail->supCosts = $quote->supCosts;
                    $creditnoteDetail->description = $quote->description;
                    $creditnoteDetail->save();
                    
                    return array('success' => true, 'creditnoteId' => $creditnote->id);
                } else {
                    return array('success' => false);
                }
                break;
                
            case 'add_quote_to_existing_invoice':
                // Add a quote to an existing invoice
                $quote = Quote::find(Request::get('quoteId'));
                $invoice = Invoice::find(Request::get('invoiceId'));
                
                if (!$quote)
                    return array('success' => false, 'msg' => 'Quote could not be found. Has it been deleted?');
                    
                    if (!$invoice)
                        return array('success' => false, 'msg' => 'Invoice ' . Request::get('invoiceId') . ' could not be found');
                        
                        $invoiceDetail = new InvoiceDetail;
                        $invoiceDetail->invoiceId = $invoice->id;
                        $invoiceDetail->productId = -999;
                        $invoiceDetail->productName = 'Quote #' . $quote->id;
                        $invoiceDetail->quoteId = $quote->id;
                        $invoiceDetail->unitPrice = $quote->getSubTotal();
                        $invoiceDetail->quantity = 1;
                        $invoiceDetail->discount = 0;
                        $invoiceDetail->supCosts = $quote->supCosts;
                        $invoiceDetail->description = $quote->description;
                        $invoiceDetail->save();
                        
                        return array('success' => true);
                        break;
                        
            case 'add_quote_to_existing_creditnote':
                // Add a quote to an existing credit note
                $quote = Quote::find(Request::get('quoteId'));
                $creditnote = Creditnote::find(Request::get('creditnoteId'));
                
                if (!$quote)
                    return array('success' => false, 'msg' => 'Quote could not be found. Has it been deleted?');
                    
                    if (!$creditnote)
                        return array('success' => false, 'msg' => 'Credit note ' . Request::get('creditnoteId') . ' could not be found');
                        
                        $invoiceDetail = new CreditnoteDetail;
                        $invoiceDetail->creditnoteId = $creditnote->id;
                        $invoiceDetail->productId = -999;
                        $invoiceDetail->productName = 'Quote #' . $quote->id;
                        $invoiceDetail->quoteId = $quote->id;
                        $invoiceDetail->unitPrice = ($quote->getSubTotal() * (-1));
                        $invoiceDetail->quantity = -1;
                        $invoiceDetail->discount = 0;
                        $invoiceDetail->supCosts = $quote->supCosts;
                        $invoiceDetail->description = $quote->description;
                        $invoiceDetail->save();
                        
                        return array('success' => true);
                        break;
                        
            case 'send_quote_email':
                $data = [
                'title' => 'Urbytus',
                'content' => nl2br(Request::get('message')),
                'footer' => '',
                'copyright' => ''
                    ];
                
                $quoteEmail = null;
                
                try {
                    Mail::send('emails.master', $data, function($email) use (&$quoteEmail) {
                        $email->from(Settings::setting('smtpSenderEmail'), Settings::setting('smtpSenderName'));
                        
                        $tos = explode(';', str_replace(',', ';', Request::get('to')));
                        $ccs = explode(';', str_replace(',', ';', Request::get('cc')));
                        $bccs = explode(';', str_replace(',', ';', Request::get('bcc')));
                        
                        if (Request::get('to') != '') {
                            foreach($tos as $to) {
                                $email->addTo($to);
                            }
                        }
                        
                        if (Request::get('cc') != '') {
                            foreach($ccs as $cc) {
                                $email->addCc($cc);
                            }
                        }
                        
                        if (Request::get('bcc') != '') {
                            foreach($bccs as $bcc) {
                                $email->addBcc($bcc);
                            }
                        }
                        
                        $email->subject(Request::get('subject'));
                        
                        if (Auth::user()->companyEmail != '')
                            $email->replyTo(Auth::user()->companyEmail, Auth::user()->getFullname());
                            
                            if (Request::get('sendcopy') === 'true' && Auth::user()->companyEmail != '')
                                $email->addBcc(Auth::user()->companyEmail);
                                
                                // Attach the PDF
                                $path = Request::root() . '/quotepdf/' . Request::get('quoteId') . '/download?nocomments=1';
                                $content = file_get_contents($path);
                                $email->attachData($content, 'Quote #' . Request::get('quoteId') . '.pdf', ['mime' => 'application/pdf']);
                                
                                // Save PDF
                                while (true) {
                                    // Get a file path for the PDF
                                    $pdfName = storage_path() . '/files/' . Settings::setting('installationId') . '/quote_emails/' . CommonFunctions::generateRandomString(10) . '.pdf';
                                    
                                    // Check if the file already exists... If it doesn't, break out of the loop
                                    if (!File::exists($pdfName))
                                        break;
                                }
                                
                                // Create the folder for this instance if it doesn't exist yet...
                                if (!File::exists(storage_path() . '/files/' . Settings::setting('installationId') . '/quote_emails'))
                                    mkdir(storage_path() . '/files/' . Settings::setting('installationId') . '/quote_emails', 0777, true);
                                    
                                    // Save the PDF
                                    $pdfFile = fopen($pdfName, 'w');
                                    fwrite($pdfFile, file_get_contents(Request::root() . '/quotepdf/' . Request::get('quoteId') . '/download'));
                                    fclose($pdfFile);
                                    
                                    // Check if we have an additional attachment. If we do, attach it to the email
                                    if (Request::hasFile('additional_attachment') && Requestfile('additional_attachment')->isValid()) {
                                        $attachment = Requestfile('additional_attachment');
                                        $attachmentData = file_get_contents($attachment->getRealPath());
                                        $email->attachData($attachmentData, $attachment->getClientOriginalName(), ['mime' => $attachment->getMimeType()]);
                                    }
                                    
                                    // Create QuoteEmail
                                    $quoteEmail = new QuoteEmail;
                                    $quoteEmail->quote = Request::get('quoteId');
                                    $quoteEmail->to = Request::get('to');
                                    $quoteEmail->cc = Request::get('cc');
                                    $quoteEmail->bcc = Request::get('bcc');
                                    $quoteEmail->subject = Request::get('subject');
                                    $quoteEmail->message = Request::get('message');
                                    $quoteEmail->filename = basename($pdfName);
                                    $quoteEmail->sentOn = CommonFunctions::getMysqlDate();
                                    $quoteEmail->sentBy = Auth::id();
                                    $quoteEmail->save();
                    });
                } catch (Swift_TransportException $ex) {
                    return array('success' => false, 'message' => $ex->getMessage());
                }
                
                // Check if we have an additional attachment. If we do, save it
                if (Request::hasFile('additional_attachment') && Requestfile('additional_attachment')->isValid()) {
                    $attachment = Requestfile('additional_attachment');
                    
                    $dbAttachment = new QuoteEmailAttachment;
                    $dbAttachment->emailId = $quoteEmail->id;
                    $dbAttachment->filename = $attachment->getClientOriginalName();
                    $dbAttachment->mime = $attachment->getMimeType();
                    $dbAttachment->size = $attachment->getSize();
                    $dbAttachment->extension = $attachment->getClientOriginalExtension();
                    $dbAttachment->save();
                    
                    $attachment->move(
                        storage_path() . '/files/' . Settings::setting('installationId') . '/quote_emails',
                        'additional_attachment_' . $quoteEmail->id);
                }
                
                // Add comment to quote
                $quote = Quote::find(Request::get('quoteId'));
                $message = 'Quote emailed. ';
                if (!empty(Request::get('to'))) {
                    $message .= '(To: ' . Request::get('to') . ') ';
                }
                if (!empty(Request::get('cc'))) {
                    $message .= '(Cc: ' . Request::get('cc') . ') ';
                }
                if (!empty(Request::get('bcc'))) {
                    $message .= '(Bcc: ' . Request::get('bcc') . ') ';
                }
                
                // This has to go manual to set the email id
                $comment = new QuoteComment;
                $comment->quoteId = $quote->id;
                $comment->placedBy = Auth::user()->id;
                $comment->placedOn = CommonFunctions::getMysqlDate();
                $comment->comment = $message;
                $comment->emailId = $quoteEmail->id;
                $comment->save();
                
                
                return array('success' => true);
                break;
                
            case 'resend_quote_email':
                $data = [
                'title' => 'Urbytus',
                'content' => nl2br(Request::get('message')),
                'footer' => '',
                'copyright' => ''
                    ];
                
                $quoteEmail = null;
                
                try {
                    Mail::send('emails.master', $data, function($email) use (&$quoteEmail) {
                        $email->from(Settings::setting('smtpSenderEmail'), Settings::setting('smtpSenderName'));
                        
                        $tos = explode(';', str_replace(',', ';', Request::get('to')));
                        $ccs = explode(';', str_replace(',', ';', Request::get('cc')));
                        $bccs = explode(';', str_replace(',', ';', Request::get('bcc')));
                        
                        if (Request::get('to') != '') {
                            foreach($tos as $to) {
                                $email->addTo($to);
                            }
                        }
                        
                        if (Request::get('cc') != '') {
                            foreach($ccs as $cc) {
                                $email->addCc($cc);
                            }
                        }
                        
                        if (Request::get('bcc') != '') {
                            foreach($bccs as $bcc) {
                                $email->addBcc($bcc);
                            }
                        }
                        
                        $email->subject(Request::get('subject'));
                        
                        if (Auth::user()->companyEmail != '')
                            $email->replyTo(Auth::user()->companyEmail, Auth::user()->getFullname());
                            
                            if (Request::get('sendcopy') === 'true' && Auth::user()->companyEmail != '')
                                $email->addBcc(Auth::user()->companyEmail);
                                
                                // Attach the (saved) PDF
                                $dbEmail = QuoteEmail::find(Request::get('emailId'));
                                $path = storage_path() . '/files/' . Settings::setting('installationId') . '/quote_emails/' . $dbEmail->filename . '.pdf';
                                if (file_exists($path)) {
                                    $content = file_get_contents($path);
                                    $email->attachData($content, 'Quote #' . Request::get('quoteId') . '.pdf', ['mime' => 'application/pdf']);
                                }
                                
                                // Save PDF
                                while (true) {
                                    // Get a file path for the PDF
                                    $pdfName = storage_path() . '/files/' . Settings::setting('installationId') . '/quote_emails/' . CommonFunctions::generateRandomString(10) . '.pdf';
                                    
                                    // Check if the file already exists... If it doesn't, break out of the loop
                                    if (!File::exists($pdfName))
                                        break;
                                }
                                
                                // Create the folder for this instance if it doesn't exist yet...
                                if (!File::exists(storage_path() . '/files/' . Settings::setting('installationId') . '/quote_emails'))
                                    mkdir(storage_path() . '/files/' . Settings::setting('installationId') . '/quote_emails', 0777, true);
                                    
                                    // Save the PDF
                                    $pdfFile = fopen($pdfName, 'w');
                                    fwrite($pdfFile, file_get_contents(Request::root() . '/quotepdf/' . Request::get('quoteId') . '/download'));
                                    fclose($pdfFile);
                                    
                                    // Create QuoteEmail
                                    $quoteEmail = new QuoteEmail;
                                    $quoteEmail->quote = Request::get('quoteId');
                                    $quoteEmail->to = Request::get('to');
                                    $quoteEmail->cc = Request::get('cc');
                                    $quoteEmail->bcc = Request::get('bcc');
                                    $quoteEmail->subject = Request::get('subject');
                                    $quoteEmail->message = Request::get('body');
                                    $quoteEmail->filename = basename($pdfName);
                                    $quoteEmail->sentOn = CommonFunctions::getMysqlDate();
                                    $quoteEmail->sentBy = Auth::id();
                                    $quoteEmail->save();
                    });
                } catch (Swift_TransportException $ex) {
                    return array('success' => false, 'message' => $ex->getMessage());
                }
                
                // Add comment to quote
                $quote = Quote::find(Request::get('quoteId'));
                $message = 'Quote emailed. ';
                if (!empty(Request::get('to'))) {
                    $message .= '(To: ' . Request::get('to') . ') ';
                }
                if (!empty(Request::get('cc'))) {
                    $message .= '(Cc: ' . Request::get('cc') . ') ';
                }
                if (!empty(Request::get('bcc'))) {
                    $message .= '(Bcc: ' . Request::get('bcc') . ') ';
                }
                
                // This has to go manual to set the email id
                $comment = new QuoteComment;
                $comment->quoteId = $quote->id;
                $comment->placedBy = Auth::user()->id;
                $comment->placedOn = CommonFunctions::getMysqlDate();
                $comment->comment = $message;
                $comment->emailId = $quoteEmail->id;
                $comment->save();
                
                
                return array('success' => true);
                break;
                
            case 'send_invoice_email':
                $data = [
                'title' => 'Urbytus',
                'content' => nl2br(Request::get('message')),
                'footer' => '',
                'copyright' => ''
                    ];
                
                $invoiceEmail = null;
                
                try {
                    Mail::send('emails.master', $data, function($email) use (&$invoiceEmail) {
                        $email->from(Settings::setting('smtpSenderEmail'), Settings::setting('smtpSenderName'));
                        
                        $tos = explode(';', str_replace(',', ';', Request::get('to')));
                        $ccs = explode(';', str_replace(',', ';', Request::get('cc')));
                        $bccs = explode(';', str_replace(',', ';', Request::get('bcc')));
                        
                        if (Request::get('to') != '') {
                            foreach($tos as $to) {
                                $email->addTo(trim($to));
                            }
                        }
                        
                        if (Request::get('cc') != '') {
                            foreach($ccs as $cc) {
                                $email->addCc(trim($cc));
                            }
                        }
                        
                        if (Request::get('bcc') != '') {
                            foreach($bccs as $bcc) {
                                $email->addBcc(trim($bcc));
                            }
                        }
                        
                        $email->subject(Request::get('subject'));
                        
                        if (Auth::user()->companyEmail != '')
                            $email->replyTo(Auth::user()->companyEmail, Auth::user()->getFullname());
                            
                            if (Request::get('sendcopy') === 'true' && Auth::user()->companyEmail != '')
                                $email->addBcc(Auth::user()->companyEmail);
                                
                                // Attach the PDF
                                $path = Request::root() . '/invoicepdf/' . Request::get('invoiceId') . '/download';
                                $content = file_get_contents($path);
                                $email->attachData($content, 'Invoice #' . Request::get('invoiceId') . '.pdf', ['mime' => 'application/pdf']);
                                
                                // Create the folder for this instance if it doesn't exist yet...
                                if (!File::exists(storage_path() . '/files/' . Settings::setting('installationId') . '/invoice_emails'))
                                    mkdir(storage_path() . '/files/' . Settings::setting('installationId') . '/invoice_emails', 0777, true);
                                    
                                    // Save PDF
                                    while (true) {
                                        // Get a file path for the PDF
                                        $pdfName = storage_path() . '/files/' . Settings::setting('installationId') . '/invoice_emails/' . CommonFunctions::generateRandomString(10) . '.pdf';
                                        
                                        // Check if the file already exists... If it doesn't, break out of the loop
                                        if (!File::exists($pdfName))
                                            break;
                                    }
                                    
                                    // Save the PDF
                                    $pdfFile = fopen($pdfName, 'w');
                                    fwrite($pdfFile, file_get_contents(Request::root() . '/invoicepdf/' . Request::get('invoiceId') . '/download'));
                                    fclose($pdfFile);
                                    
                                    // Check if we have an additional attachment. If we do, attach it to the email
                                    if (Request::hasFile('additional_attachment') && Requestfile('additional_attachment')->isValid()) {
                                        $attachment = Requestfile('additional_attachment');
                                        $attachmentData = file_get_contents($attachment->getRealPath());
                                        $email->attachData($attachmentData, $attachment->getClientOriginalName(), ['mime' => $attachment->getMimeType()]);
                                    }
                                    
                                    // Create InvoiceEmail
                                    $invoiceEmail = new InvoiceEmail;
                                    $invoiceEmail->invoice = Request::get('invoiceId');
                                    $invoiceEmail->to = Request::get('to');
                                    $invoiceEmail->cc = Request::get('cc');
                                    $invoiceEmail->bcc = Request::get('bcc');
                                    $invoiceEmail->subject = Request::get('subject');
                                    $invoiceEmail->body = Request::get('message');
                                    $invoiceEmail->filename = basename($pdfName);
                                    $invoiceEmail->sentOn = CommonFunctions::getMysqlDate();
                                    $invoiceEmail->sentBy = Auth::id();
                                    $invoiceEmail->save();
                    });
                } catch (Swift_TransportException $ex) {
                    return array('success' => false, 'message' => $ex->getMessage());
                }
                
                // Check if we have an additional attachment. If we do, save it
                if (Request::hasFile('additional_attachment') && Requestfile('additional_attachment')->isValid()) {
                    $attachment = Requestfile('additional_attachment');
                    
                    $dbAttachment = new InvoiceEmailAttachment;
                    $dbAttachment->emailId = $invoiceEmail->id;
                    $dbAttachment->filename = $attachment->getClientOriginalName();
                    $dbAttachment->mime = $attachment->getMimeType();
                    $dbAttachment->size = $attachment->getSize();
                    $dbAttachment->extension = $attachment->getClientOriginalExtension();
                    $dbAttachment->save();
                    
                    $attachment->move(
                        storage_path() . '/files/' . Settings::setting('installationId') . '/invoice_emails',
                        'additional_attachment_' . $invoiceEmail->id);
                }
                
                // Add comment to invoice
                $message = 'Invoice emailed. ';
                if (!empty(Request::get('to'))) {
                    $message .= '(To: ' . Request::get('to') . ') ';
                }
                if (!empty(Request::get('cc'))) {
                    $message .= '(Cc: ' . Request::get('cc') . ') ';
                }
                if (!empty(Request::get('bcc'))) {
                    $message .= '(Bcc: ' . Request::get('bcc') . ') ';
                }
                
                // This has to go manual to set the email id
                $comment = new InvoiceComment;
                $comment->invoiceId = Request::get('invoiceId');
                $comment->placedBy = Auth::user()->id;
                $comment->placedOn = CommonFunctions::getMysqlDate();
                $comment->comment = $message;
                $comment->emailId = $invoiceEmail->id;
                $comment->save();
                
                return array('success' => true);
                break;
                
            case 'resend_invoice_email':
                $data = [
                'title' => 'Urbytus',
                'content' => nl2br(Request::get('message')),
                'footer' => '',
                'copyright' => ''
                    ];
                
                $invoiceEmail = null;
                
                try {
                    Mail::send('emails.master', $data, function($email) use (&$invoiceEmail) {
                        $email->from(Settings::setting('smtpSenderEmail'), Settings::setting('smtpSenderName'));
                        
                        $tos = explode(';', str_replace(',', ';', Request::get('to')));
                        $ccs = explode(';', str_replace(',', ';', Request::get('cc')));
                        $bccs = explode(';', str_replace(',', ';', Request::get('bcc')));
                        
                        if (Request::get('to') != '') {
                            foreach($tos as $to) {
                                $email->addTo($to);
                            }
                        }
                        
                        if (Request::get('cc') != '') {
                            foreach($ccs as $cc) {
                                $email->addCc($cc);
                            }
                        }
                        
                        if (Request::get('bcc') != '') {
                            foreach($bccs as $bcc) {
                                $email->addBcc($bcc);
                            }
                        }
                        
                        $email->subject(Request::get('subject'));
                        
                        if (Auth::user()->companyEmail != '')
                            $email->replyTo(Auth::user()->companyEmail, Auth::user()->getFullname());
                            
                            if (Request::get('sendcopy') === 'true' && Auth::user()->companyEmail != '')
                                $email->addBcc(Auth::user()->companyEmail);
                                
                                // Attach the (saved) PDF
                                $dbEmail = InvoiceEmail::find(Request::get('emailId'));
                                $path = storage_path() . '/files/' . Settings::setting('installationId') . '/invoice_emails/' . $dbEmail->filename . '.pdf';
                                $content = file_get_contents($path);
                                $email->attachData($content, 'Invoice #' . Request::get('invoiceId') . '.pdf', ['mime' => 'application/pdf']);
                                
                                // Save PDF
                                while (true) {
                                    // Get a file path for the PDF
                                    $pdfName = storage_path() . '/files/' . Settings::setting('installationId') . '/invoice_emails/' . CommonFunctions::generateRandomString(10) . '.pdf';
                                    
                                    // Check if the file already exists... If it doesn't, break out of the loop
                                    if (!File::exists($pdfName))
                                        break;
                                }
                                
                                // Create the folder for this instance if it doesn't exist yet...
                                if (!File::exists(storage_path() . '/files/' . Settings::setting('installationId') . '/invoice_emails'))
                                    mkdir(storage_path() . '/files/' . Settings::setting('installationId') . '/invoice_emails', 0777, true);
                                    
                                    // Save the PDF
                                    $pdfFile = fopen($pdfName, 'w');
                                    fwrite($pdfFile, file_get_contents(Request::root() . '/invoicepdf/' . Request::get('invoiceId') . '/download'));
                                    fclose($pdfFile);
                                    
                                    // Create InvoiceEmail
                                    $invoiceEmail = new InvoiceEmail;
                                    $invoiceEmail->invoice = Request::get('invoiceId');
                                    $invoiceEmail->to = Request::get('to');
                                    $invoiceEmail->cc = Request::get('cc');
                                    $invoiceEmail->bcc = Request::get('bcc');
                                    $invoiceEmail->subject = Request::get('subject');
                                    $invoiceEmail->body = Request::get('message');
                                    $invoiceEmail->filename = basename($pdfName);
                                    $invoiceEmail->sentOn = CommonFunctions::getMysqlDate();
                                    $invoiceEmail->sentBy = Auth::id();
                                    $invoiceEmail->save();
                    });
                } catch (Swift_TransportException $ex) {
                    return array('success' => false, 'message' => $ex->getMessage());
                }
                
                // Add comment to invoice
                $invoice = Invoice::find(Request::get('invoiceId'));
                $message = 'Invoice emailed. ';
                if (!empty(Request::get('to'))) {
                    $message .= '(To: ' . Request::get('to') . ') ';
                }
                if (!empty(Request::get('cc'))) {
                    $message .= '(Cc: ' . Request::get('cc') . ') ';
                }
                if (!empty(Request::get('bcc'))) {
                    $message .= '(Bcc: ' . Request::get('bcc') . ') ';
                }
                
                // This has to go manual to set the email id
                $comment = new InvoiceComment;
                $comment->invoiceId = $invoice->id;
                $comment->placedBy = Auth::user()->id;
                $comment->placedOn = CommonFunctions::getMysqlDate();
                $comment->comment = $message;
                $comment->emailId = $invoiceEmail->id;
                $comment->save();
                
                
                return array('success' => true);
                break;
                
            case 'send_creditnote_email':
                $data = [
                'title' => 'Urbytus',
                'content' => nl2br(Request::get('message')),
                'footer' => '',
                'copyright' => ''
                    ];
                
                try {
                    Mail::send('emails.master', $data, function($email) {
                        $email->from(Settings::setting('smtpSenderEmail'), Settings::setting('smtpSenderName'));
                        
                        $tos = explode(';', str_replace(',', ';', Request::get('to')));
                        $ccs = explode(';', str_replace(',', ';', Request::get('cc')));
                        $bccs = explode(';', str_replace(',', ';', Request::get('bcc')));
                        
                        if (Request::get('to') != '') {
                            foreach($tos as $to) {
                                $email->addTo(trim($to));
                            }
                        }
                        
                        if (Request::get('cc') != '') {
                            foreach($ccs as $cc) {
                                $email->addCc(trim($cc));
                            }
                        }
                        
                        if (Request::get('bcc') != '') {
                            foreach($bccs as $bcc) {
                                $email->addBcc(trim($bcc));
                            }
                        }
                        
                        $email->subject(Request::get('subject'));
                        
                        if (Auth::user()->companyEmail != '')
                            $email->replyTo(Auth::user()->companyEmail, Auth::user()->getFullname());
                            
                            if (Request::get('sendcopy') === 'true' && Auth::user()->companyEmail != '')
                                $email->addBcc(Auth::user()->companyEmail);
                                
                                // Attach the PDF
                                $path = Request::root() . '/creditnotepdf/' . Request::get('creditnoteId') . '/download';
                                $content = file_get_contents($path);
                                $email->attachData($content, 'Creditnote #' . Request::get('creditnoteId') . '.pdf', ['mime' => 'application/pdf']);
                                
                                // Create the folder for this instance if it doesn't exist yet...
                                if (!File::exists(storage_path() . '/files/' . Settings::setting('installationId') . '/creditnote_emails'))
                                    mkdir(storage_path() . '/files/' . Settings::setting('installationId') . '/creditnote_emails', 0777, true);
                                    
                                    // Save PDF
                                    while (true) {
                                        // Get a file path for the PDF
                                        $pdfName = storage_path() . '/files/' . Settings::setting('installationId') . '/creditnote_emails/' . CommonFunctions::generateRandomString(10) . '.pdf';
                                        
                                        // Check if the file already exists... If it doesn't, break out of the loop
                                        if (!File::exists($pdfName))
                                            break;
                                    }
                                    
                                    // Save the PDF
                                    $pdfFile = fopen($pdfName, 'w');
                                    fwrite($pdfFile, file_get_contents(Request::root() . '/creditnotepdf/' . Request::get('creditnoteId') . '/download'));
                                    fclose($pdfFile);
                                    
                                    // Create CreditnoteEmail
                                    $quoteEmail = new CreditnoteEmail;
                                    $quoteEmail->creditnote = Request::get('creditnoteId');
                                    $quoteEmail->to = Request::get('to');
                                    $quoteEmail->cc = Request::get('cc');
                                    $quoteEmail->bcc = Request::get('bcc');
                                    $quoteEmail->subject = Request::get('subject');
                                    $quoteEmail->body = Request::get('message');
                                    $quoteEmail->filename = basename($pdfName);
                                    $quoteEmail->sentOn = CommonFunctions::getMysqlDate();
                                    $quoteEmail->sentBy = Auth::id();
                                    $quoteEmail->save();
                    });
                } catch (Swift_TransportException $ex) {
                    return array('success' => false, 'message' => $ex->getMessage());
                }
                
                // Add comment to quote
                $quote = Creditnote::find(Request::get('creditnoteId'));
                $message = 'Creditnote emailed. ';
                if (!empty(Request::get('to'))) {
                    $message .= '(To: ' . Request::get('to') . ') ';
                }
                if (!empty(Request::get('cc'))) {
                    $message .= '(Cc: ' . Request::get('cc') . ') ';
                }
                if (!empty(Request::get('bcc'))) {
                    $message .= '(Bcc: ' . Request::get('bcc') . ') ';
                }
                
                $quote->addComment($message);
                
                return array('success' => true);
                break;
                
            case 'invoice_add_to_direct_debit_queue':
                $invoice = Invoice::find(Request::get('invoiceId'));
                
                if (!$invoice)
                    return;
                    
                    $ddDetail = new DirectDebitDetail;
                    $ddDetail->job = 0;
                    $ddDetail->customer = $invoice->customer;
                    $ddDetail->invoice = $invoice->id;
                    $ddDetail->description = Request::get('description');
                    $ddDetail->sent = 0;
                    $ddDetail->debited = 0;
                    $ddDetail->bankCharge = (Request::get('applyBankCharge') == 'true') ? Request::get('bankCharge') : 0;
                    $ddDetail->total = $invoice->getTotal();
                    $ddDetail->save();
                    
                    return array('success' => true);
                    break;
                    
            case 'get_new_direct_debit_job_data':
                // First, get the max job id + 1
                $returnData = [];
                
                $returnData['provisionalJobId'] = DirectDebitJob::max('id')+1;
                $returnData['queueInvoices'] = DirectDebitDetail::where('job', '=', 0)->get()->toArray();
                
                return $returnData;
                break;
                
            case 'save_direct_debit_job':
                foreach(Request::get('invoices') as $invoice) {
                    if (DirectDebitController::getInvoiceValidation($invoice) != '<ul></ul>')
                        return array('success' => false, 'msg' => 'Data integrity check failed. Please run the data integrity check first!');
                }
                
                // Create a new job
                $job = new DirectDebitJob;
                $job->date = date('Y-m-d', strtotime(Request::get('debitDate')));
                $job->completed = 0; // ???
                $job->description = Request::get('description');
                $job->save();
                
                
                // Get the invoices
                $invoices = DirectDebitDetail::whereIn('id', Request::get('invoices'))->get();
                
                // Calculate the total for the batch
                $total = 0;
                foreach($invoices as $invoice) {
                    $total += ($invoice->total + $invoice->bankCharge);
                    $invoice->job = $job->id;
                    $invoice->sent = 1;
                    $invoice->debited = 0;
                    $invoice->save();
                }
                
                return array('success' => true, 'jobId' => $job->id);
                break;
                
            case 'save_customer_notes':
                $customer = Customer::find(Request::get('customerId'));
                
                if ($customer) {
                    $customer->fill(Requestexcept('customerId', 'iban', 'addresses'));
                    
                    if (Request::get('iban') == '')
                        $customer->iban = '';
                        
                        if (Request::get('iban') != '') {
                            $customer->iban = preg_replace("/[^A-Za-z0-9]/", '', Request::get('iban'));
                        }
                        
                        if (is_array(Request::get('addresses')) && count(Request::get('addresses')) > 0) {
                            foreach(Request::get('addresses') as $address) {
                                $dbAddress = Address::find($address['id']);
                                
                                if (!$dbAddress)
                                    continue;
                                    
                                    $dbAddress->label = $address['label'];
                                    $dbAddress->address = $address['address'];
                                    $dbAddress->city = $address['city'];
                                    $dbAddress->postalcode = $address['postalcode'];
                                    $dbAddress->province = $address['province'];
                                    $dbAddress->country = $address['country'];
                                    $dbAddress->telephone = $address['telephone'];
                                    $dbAddress->email = $address['email'];
                                    $dbAddress->save();
                            }
                        }
                        
                        $customer->save();
                        
                        return array('success' => true);
                }
                break;
                
            case 'save_customer_location':
                $customer = Customer::find(Request::get('customerId'));
                
                if ($customer) {
                    $customer->locationLat = Request::get('lat');
                    $customer->locationLng = Request::get('lng');
                    $customer->save();
                    
                    return array('success' => true);
                }
                break;
                
            case 'disable_customer_location':
                $customer = Customer::find(Request::get('customerId'));
                
                if ($customer) {
                    $customer->locationLat = null;
                    $customer->locationLng = null;
                    $customer->save();
                    
                    return array('success' => true);
                }
                break;
                
            case 'search_customer':
                $customers = Customer::where('companyName', 'LIKE', '%' . Request::get('query') . '%')->orWhere('contactName', 'LIKE', '%' . Request::get('query') . '%')->get();
                
                return array('customers' => $customers->toArray());
                break;
                
            case 'search_customer_id':
                $customers = Customer::where('companyName', 'LIKE', '%' . Request::get('query') . '%')->orWhere('contactName', 'LIKE', '%' . Request::get('query') . '%')->select('id', 'companyName')->get();
                return array('customers' => $customers->toArray());
                break;
                
            case 'get_quote_overview':
                $quote = Quote::find(Request::get('quoteId'));
                
                if (!$quote)
                    return array('success' => false);
                    
                    return array('success' => true, 'quote' => $quote->toArray());
                    break;
                    
            case 'save_payment':
                $payment = new Payment;
                
                $paymentData = json_decode(json_encode(Request::get('paymentData')));
                
                $payment->date = date('Y-m-d H:i:s', strtotime($paymentData->date));
                
                $total = 0;
                
                foreach(Request::get('paymentNotes') as $note => $amount) {
                    $total += (float) $note * (int) $amount;
                    $payment['n' . $note] = $amount;
                }
                
                foreach(Request::get('paymentCoins') as $coin => $amount) {
                    $total += (float) $coin * (int) $amount;
                    $payment['c' . $coin] = $amount;
                }
                
                $payment->createdBy = Auth::id();
                $payment->paymentType = $paymentData->paymentMethod;
                $payment->notes = $paymentData->notes;
                $payment->nonCash = $paymentData->nonCashTotal;
                $payment->customerId = $paymentData->customer;
                $payment->outToBank = ($paymentData->outToBank == true) ? 1 : 0;
                $payment->checkedByManagement = ($paymentData->checkedByManagement == true) ? 1 : 0;
                $payment->save();
                
                foreach(Request::get('entries') as $entry) {
                    $entry = json_decode(json_encode($entry));
                    $newEntry = new PaymentDetail;
                    $newEntry->paymentId = $payment->id;
                    $newEntry->date = date('Y-m-d', strtotime($entry->date));
                    $newEntry->quoteId = $entry->quote;
                    $newEntry->amount = $entry->amount;
                    $newEntry->save();
                }
                
                
                return array('success' => true);
                
                break;
                
            case 'update_quote_status':
                $quote = Quote::find(Request::get('quoteId'));
                
                if (!$quote)
                    return array('success' => false);
                    
                    // If status has chanegd, insert comment
                    if (Request::get('newStatus') != $quote->status) {
                        $quote->addComment('Status changed from ' . $quote->getStatus->type . ' to ' . JobStatus::find(Request::get('newStatus'))->type);
                    }
                    
                    $quote->status = Request::get('newStatus');
                    $quote->save();
                    
                    return array('success' => true);
                    break;
                    
            case 'save_development_notes':
                $settings = Settings::find(1);
                $settings->developmentNotes = Request::get('notes');
                $settings->save();
                break;
                
            case 'save_supplier_notes':
                $supplier = Supplier::find(Request::get('supplierId'));
                if (!$supplier)
                    return array('success' => false);
                    
                    $supplier->notes = Request::get('notes');
                    $supplier->save();
                    
                    return array('success' => true);
                    break;
                    
            case 'search_anything_query':
                $query = Request::get('query');
                
                $customersByPhone = Customer::where('phone', 'LIKE', '%' . $query . '%')->orWhere('mobile', 'LIKE', '%' . $query . '%')->orderBy('companyName')->get()->toArray();
                
                $customers = Customer::where('companyName', 'LIKE', '%' . $query . '%')
                ->orWhere('contactName', 'LIKE', '%' . $query . '%')
                ->orWhere('customerCode', 'LIKE', '%' . $query . '%')
                ->orWhere('email', 'LIKE', '%' . $query . '%')
                ->orWhere('cifnif', 'LIKE', '%' . $query . '%')
                ->orWhere('fax', 'LIKE', '%' . $query . '%')
                ->orWhere('website', 'LIKE', '%' . $query . '%')
                ->orWhere('shopName', 'LIKE', '%' . $query . '%')
                ->orderBy('companyName')->get()->toArray();
                
                $suppliers = Supplier::where('supplierCode', 'LIKE', '%' . $query . '%')
                ->orWhere('companyName', 'LIKE', '%' . $query . '%')
                ->orWhere('contactName', 'LIKE', '%' . $query . '%')
                ->orWhere('phone', 'LIKE', '%' . $query . '%')
                ->orWhere('mobile', 'LIKE', '%' . $query . '%')
                ->orWhere('notes', 'LIKE', '%' . $query . '%')
                ->orWhere('tradingName', 'LIKE', '%' . $query . '%')
                ->orWhere('notes', 'LIKE', '%' . $query . '%')
                ->orWhere('services', 'LIKE', '%' . $query . '%')
                ->orderBy('companyName')->get()->toArray();
                
                $products = Product::where('name', 'LIKE', '%' . $query . '%')->orderBy('name')->get()->toArray();
                $quotes = Quote::where('id', '=', $query)->get()->toArray();
                $invoices = Invoice::where('id', '=', $query)->get()->toArray();
                
                $users = User::where('firstname', 'LIKE', '%' . $query . '%')
                ->orWhere('lastname', 'LIKE', '%' . $query . '%')
                ->orWhere('initials', 'LIKE', '%' . $query . '%')
                ->orWhere('username', 'LIKE', '%' . $query . '%')
                ->orderBy('lastname')->select('id', 'firstname', 'lastname')->get()->toArray();
                
                $contacts = Customer::where('contactName', 'LIKE', '%' . $query . '%')->orderBy('contactName')->get()->toArray();
                
                $result = array(
                    'results' => [
                        'customersByPhone' => $customersByPhone,
                        'customers' => $customers,
                        'suppliers' => $suppliers,
                        'products' => $products,
                        'quotes' => $quotes,
                        'invoices' => $invoices,
                        'users' => $users,
                        'contacts' => $contacts
                    ]
                );
                
                return $result;
                break;
                
            case 'validate_direct_debit_data':
                $invoices = DirectDebitDetail::whereIn('id', Request::get('invoices'))->get();
                
                $total = 0;
                foreach($invoices as $invoice) {
                    $total += $invoice->total;
                }
                
		return View::make('directdebit.integritycheck')
                ->with('invoices', $invoices)
                ->with('total', $total);
                
                return array('errors' => $errors);
                break;
                
            case 'new_user_group':
                if (UserGroup::where('name', '=', Request::get('groupName'))->count() != 0) {
                    return array('success' => false, 'error' => 'A user group with that name already exists!');
                }
                
                $userGroup = new UserGroup;
                $userGroup->name = Request::get('groupName');
                $userGroup->save();
                
                return array('success' => true);
                break;
                
            case 'log_ajax_error':
                $ajaxError = new AjaxError;
                $ajaxError->occuredOn = CommonFunctions::getMysqlDate();
                $ajaxError->ajaxId = Request::get('ajaxId');
                $ajaxError->data = json_encode(Request::get('data'));
                $ajaxError->status = Request::get('status');
                $ajaxError->errorThrown = Request::get('errorThrown');
                $ajaxError->url = $_SERVER["HTTP_REFERER"];
                $ajaxError->body = json_encode(Request::get('body'));
                $ajaxError->save();
                break;
                
            case 'delete_invoice':
                // Find the invoice
                $invoice = Invoice::find(Request::get('invoiceId'));
                
                if (!$invoice)
                    return array('success' => false, 'msg' => 'Could not find invoice');
                    
                    if ($invoice->id != Invoice::max('id'))
                        return array('success' => false, 'msg' => 'This invoice is not the last invoice');
                        
                        // Delete invoice details
                        InvoiceDetail::where('invoiceId', '=', $invoice->id)->delete();
                        $invoiceId = $invoice->id;
                        $invoice->delete();
                        
                        // Delete the invoice comments
                        InvoiceComment::where('invoiceId', '=', $invoiceId)->delete();
                        
                        // Now that the invoice is gone, we have to reset the auto increment value so the next invoice will have the same invoice id
                        DB::statement('ALTER TABLE invoices AUTO_INCREMENT=' . $invoiceId);
                        
                        return array('success' => true);
                        break;
                        
            case 'delete_creditnote':
                // Find the creditnote
                $creditnote = Creditnote::find(Request::get('creditnoteId'));
                
                if (!$creditnote)
                    return array('success' => false, 'msg' => 'Could not find creditnote');
                    
                    if ($creditnote->id != Creditnote::max('id'))
                        return array('success' => false, 'msg' => 'This creditnote is not the last creditnote');
                        
                        // Delete creditnote details
                        CreditnoteDetail::where('creditnoteId', '=', $creditnote->id)->delete();
                        $creditnoteId = $creditnote->id;
                        $creditnote->delete();
                        
                        // Delete the creditnote comments
                        CreditnoteComment::where('creditnoteId', '=', $creditnoteId)->delete();
                        
                        // Now that the creditnote is gone, we have to reset the auto increment value so the next creditnote will have the same creditnote id
                        DB::statement('ALTER TABLE creditnotes AUTO_INCREMENT=' . $creditnoteId);
                        
                        return array('success' => true);
                        break;
                        
            case 'void_invoice':
                $invoice = Invoice::find(Request::get('invoiceId'));
                
                if (!$invoice)
                    return array('success' => false, 'msg' => 'Could not find invoice');
                    
                    // Create new invoice
                    $newInvoice = new Invoice;
                    $newInvoice->id = (Invoice::max('id') + 1);
                    $newInvoice->createdOn = CommonFunctions::getMysqlDate();
                    $newInvoice->customer = $invoice->customer;
                    $newInvoice->jobTitle = $invoice->jobTitle;
                    $newInvoice->createdBy = Auth::id();
                    $newInvoice->vat = $invoice->vat;
                    $newInvoice->description = $invoice->description;
                    $newInvoice->notes = $invoice->notes;
                    $newInvoice->save();
                    
                    // Duplicate invoice details
                    $entries = $invoice->getEntries;
                    foreach($entries as $entry) {
                        $newEntry = new InvoiceDetail;
                        $newEntry->invoiceId = $newInvoice->id;
                        $newEntry->productId = -999;
                        $newEntry->productName = $entry->productName;
                        $newEntry->quoteId = $entry->quoteId;
                        $newEntry->unitPrice = $entry->unitPrice;
                        $newEntry->quantity = -$entry->quantity;
                        $newEntry->discount = $entry->discount;
                        $newEntry->supCosts = -$entry->supCosts;
                        $newEntry->description = $entry->description;
                        $newEntry->save();
                    }
                    
                    return array('invoiceId' => $newInvoice->id);
                    break;
                    
            case 'update_renewal_notes':
                $renewal = Renewal::find(Request::get('renewalId'));
                
                if (!$renewal)
                    return array('success' => false);
                    
                    $renewal->notes = Request::get('notes');
                    $renewal->save();
                    
                    return array('success' => true);
                    break;
                    
            case 'search_product':
                $products = Product::where('discontinued', '=', 0)->where(function($query) {
                    $query->where('name', 'LIKE', '%' . Request::get('query') . '%')
                    ->orWhere('id', '=', Request::get('query'));
                })->get();
                return $products;
                break;
                
            case 'delete_quote_comment':
                $comment = QuoteComment::find(Request::get('commentId'));
                if ($comment) {
                    $comment->delete();
                    return array('success' => true);
                }
                break;
                
            case 'delete_invoice_comment':
                $comment = InvoiceComment::find(Request::get('commentId'));
                if ($comment) {
                    $comment->delete();
                    return array('success' => true);
                }
                break;
                
            case 'update_quote_comment':
                $comment = QuoteComment::find(Request::get('commentId'));
                
                if ($comment) {
                    $comment->comment = Request::get('newComment');
                    $comment->save();
                    return array('success' => true);
                }
                break;
                
            case 'update_invoice_comment':
                $comment = InvoiceComment::find(Request::get('commentId'));
                
                if ($comment) {
                    $comment->comment = Request::get('newComment');
                    $comment->save();
                    return array('success' => true);
                }
                break;
                
            case 'update_creditnote_comment':
                $comment = CreditnoteComment::find(Request::get('commentId'));
                
                if ($comment) {
                    $comment->comment = Request::get('newComment');
                    $comment->save();
                    return array('success' => true);
                }
                break;
                
            case 'delete_invoice_from_direct_debit_queue':
                DirectDebitDetail::where('id', '=', Request::get('detailId'))->where('job', '=', '0')->delete();
                
                return array('success' => true);
                break;
                
            case 'customer_show_save_quick_info_settings':
                sleep(1);
                return array('success' => true);
                break;
                
            case 'create_reminder':
                foreach(Request::get('sendTo') as $recipient) {
                    $reminder = new Reminder;
                    
                    $reminder->user = $recipient;
                    $reminder->title = Request::get('title');
                    $reminder->description = Request::get('description');
                    $reminder->url = Request::get('page');
                    $reminder->reminderDate = date('Y-m-d H:i:s', strtotime(Request::get('date')));
                    $reminder->createdOn = CommonFunctions::getMysqlDate();
                    $reminder->createdBy = Auth::id();
                    $reminder->sentTo = implode(',', Request::get('sendTo'));
                    $reminder->sentToOutlook = (Request::get('sendToOutlook') && Request::get('sendToOutlook') == 'true') ? 1 : 0;
                    $reminder->dismissed = 0;
                    $reminder->read = 0;
                    
                    $reminder->save();
                    
                    // Send outlook event if send to outlook is ticked
                    if (Request::get('sendToOutlook') && Request::get('sendToOutlook') == 'true' && Settings::hasValidEmailSettings()) {
                        $startDate = DateTime::createFromFormat('d-m-Y H:i', Request::get('date'));
                        $endDate = clone $startDate;
                        $endDate->add(new DateInterval('PT5M'));
                        
                        // Send the email if send to outlook is ticked
                        foreach(Request::get('sendTo') as $receiver) {
                            // Check if we have the user
                            $receiver = User::find($receiver);
                            if (!$receiver || $receiver->companyEmail == '')
                                continue;
                                
                                $icalendar = IcalendarUtil::genEvent(
                                    array('Pepper CRM - ' . Auth::user()->firstname, 'noreply@pepper-crm.net'),
                                    $startDate,
                                    $endDate,
                                    [
                                        $receiver->companyEmail => $receiver->getFullname()
                                    ],
                                    'Pepper CRM Reminder: ' . Request::get('title'),
                                    'Pepper CRM',
                                    Request::get('description'),
                                    true,
                                    false
                                    );
                                
                                
                                Mail::send('nada', array(), function($message) use($icalendar, $receiver)
                                {
                                    $message->from(Auth::user()->companyEmail, Auth::user()->getFullname());
                                    $message->to($receiver->companyEmail)->subject('Pepper reminder: ' . Request::get('title'));
                                    
                                    $encoder = \Swift_Encoding::get7BitEncoding();
                                    $message->setEncoder($encoder);
                                    
                                    $message->addPart($icalendar, 'text/calendar; method=REQUEST', 'iso-8859-1');
                                });
                        }
                    }
                }
                
                // Return success
                return array('success' => true);
                break;
                
            case 'dismiss_reminder':
                $reminder = Reminder::find(Request::get('reminderId'));
                
                if (!$reminder || $reminder->user != Auth::id())
                    return array('success' => false, 'msg' => 'Reminder could not be found. Has it been deleted?');
                    
                    $reminder->dismissed = true;
                    $reminder->save();
                    
                    return array('success' => true);
                    break;
                    
            case 'snooze_reminder':
                $reminder = Reminder::find(Request::get('reminderId'));
                
                if (!$reminder || $reminder->user != Auth::id())
                    return array('success' => false, 'msg' => 'Reminder could not be found. Has 	it been deleted?');
                    
                    $reminder->reminderDate = date('Y-m-d H:i:s', time() + Request::get('duration'));
                    $reminder->save();
                    
                    return array('success' => true);
                    break;
                    
            case 'get_create_expense_data':
                return compact('suppliers', 'categories', 'subcategories');
                
                break;
                
            case 'search_supplier':
                $results = Supplier::where('companyName', 'LIKE', '%' . Request::get('query') . '%')->orWhere('tradingName', 'LIKE', '%' . Request::get('query') . '%')->get();
                
                return $results;
                break;
                
            case 'get_expense_subcategories':
                return ExpenseSubcategory::where('category', '=', Request::get('mainCategory'))->get();
                break;
                
            case 'save_expense':
                // Figure out if it's a new or existing expense
                $isEdit = array_key_exists('id', Request::get('expense'));
                
                $expenseData = Request::get('expense');
                
                if ($isEdit) {
                    // Existing, so update
                    $expense = Expense::find($expenseData['id']);
                    
                    if (!$expense)
                        return array('success' => false);
                        
                        // Check if the proforma already exists in another expense for the same supplier
                        if ($expenseData['proforma'] != '') {
                            $proformaCheck = Expense::where('supplier', '=', $expense->supplier)->where('id', '!=', $expense->id)->where('proforma', '=', $expenseData['proforma'])->count();
                            if ($proformaCheck > 0)
                                return ['success' => false, 'message' => 'Could not save expense. An expense already exists for the same supplier with the same proforma number'];
                        }
                        
                        
                        ////////////////////////
                        ///// Save expense /////
                        ////////////////////////
                        $saveExpense = json_decode(json_encode($expenseData));
                        
                        unset($saveExpense->payments);
                        unset($saveExpense->products);
                        unset($saveExpense->subtotals);
                        
                        $saveExpense->invoiceDate = date('Y-m-d', strtotime($saveExpense->invoiceDate));
                        $saveExpense->invoiceReceivedDate = date('Y-m-d', strtotime($saveExpense->invoiceReceivedDate));
                        $saveExpense->isOfficial = ($saveExpense->isOfficial == 'true') ? 1 : 0;
                        $saveExpense->isInternal = ($saveExpense->isInternal == 'true') ? 1 : 0;
                        $saveExpense->waitingForInvoice = ($saveExpense->waitingForInvoice == 'true') ? 1 : 0;
                        
                        $expense->fill((array) $saveExpense);
                        $expense->save();
                        
                        
                        ////////////////////////
                        //// Save subtotals ////
                        ////////////////////////
                        
                        if (array_key_exists('subtotals', $expenseData)) {
                            $subtotals = $expenseData['subtotals'];
                            $idsDone = [];
                            
                            foreach($subtotals as $subtotal) {
                                if ($subtotal['id'] == -1) {
                                    // New
                                    $newSubtotal = new ExpenseSubtotal;
                                    $newSubtotal->expense = $expense['id'];
                                    $newSubtotal->subtotal = $subtotal['subtotal'];
                                    $newSubtotal->vat = $subtotal['vat'];
                                    
                                    $newSubtotal->save();
                                    
                                    $idsDone[] = $newSubtotal->id;
                                } else {
                                    // Existing
                                    $existingSubtotal = ExpenseSubtotal::find($subtotal['id']);
                                    if (!$existingSubtotal)
                                        return array('success' => false, 'msg' => 'Subtotal #' . $subtotal['id'] . ' could not be found');
                                        
                                        $existingSubtotal->subtotal = $subtotal['subtotal'];
                                        $existingSubtotal->vat = $subtotal['vat'];
                                        
                                        $existingSubtotal->save();
                                        
                                        $idsDone[] = $existingSubtotal->id;
                                }
                            }
                            // Delete deleted subtotals
                            ExpenseSubtotal::where('expense', '=', $expense->id)->whereNotIn('id', $idsDone)->delete();
                        } else {
                            // Remove them bitches
                            ExpenseSubtotal::where('expense', '=', $expense->id)->delete();
                        }
                        
                        ////////////////////////
                        ///// Save payments ////
                        ////////////////////////
                        
                        // $expenseData['payments'] doesn't exist if no payments are in the list, so check it
                        if (array_key_exists('payments', $expenseData)) {
                            $payments = $expenseData['payments'];
                            $paymentIdsDone = array();
                            foreach($payments as $payment) {
                                if ($payment['id'] == -1) {
                                    // New
                                    $newPayment = new ExpensePayment;
                                    $newPayment->expense = $expense->id;
                                    $newPayment->description = $payment['description'];
                                    $newPayment->date = CommonFunctions::parseMaskedDateTime($payment['date']);
                                    $newPayment->amount = $payment['amount'];
                                    $newPayment->paymentMethod = $payment['paymentMethod'];
                                    $newPayment->save();
                                    
                                    $paymentIdsDone[] = $newPayment->id;
                                    
                                    // Insert entries
                                    $paymentDetails = $payment['entries'];
                                    
                                    if (is_array($paymentDetails) && count($paymentDetails) > 0) {
                                        foreach($paymentDetails as $paymentDetail) {
                                            $newDetail = new ExpensePaymentDetail;
                                            $newDetail->expensePayment = $newPayment->id;
                                            $newDetail->invoiceId = $paymentDetail['invoiceId'];
                                            $newDetail->amount = $paymentDetail['amount'];
                                            $newDetail->save();
                                        }
                                    }
                                } else {
                                    // Existing
                                    $existingPayment = ExpensePayment::find($payment['id']);
                                    
                                    if (!$existingPayment)
                                        return array('success' => false, 'msg' => 'Payment #' . $payment->id . ' could not be found');
                                        
                                        $existingPayment->description = $payment['description'];
                                        $existingPayment->date = CommonFunctions::parseMaskedDateTime($payment['date']);
                                        $existingPayment->amount = $payment['amount'];
                                        $existingPayment->paymentMethod = $payment['paymentMethod'];
                                        $existingPayment->save();
                                        
                                        $paymentIdsDone[] = $existingPayment->id;
                                        
                                        // Update entries
                                        $paymentDetails = (array_key_exists('entries', $payment)) ? $payment['entries'] : [];
                                        if (is_array($paymentDetails) && count($paymentDetails) > 0) {
                                            $paymentDetailIdsDone = [];
                                            foreach($paymentDetails as $paymentDetail) {
                                                // New or existing
                                                if ($paymentDetail['id'] != -1) {
                                                    // EXISTING
                                                    $existingPaymentDetail = ExpensePaymentDetail::find($paymentDetail['id']);
                                                    $existingPaymentDetail->expensepayment = $expense->id;
                                                    $existingPaymentDetail->invoiceId = $paymentDetail['invoiceId'];
                                                    $existingPaymentDetail->amount = $paymentDetail['amount'];
                                                    $existingPaymentDetail->save();
                                                    
                                                    $paymentDetailIdsDone[] = $existingPaymentDetail->id;
                                                } else {
                                                    // NEW
                                                    $newPaymentDetail = new ExpensePaymentDetail;
                                                    $newPaymentDetail->expensepayment = $expense->id;
                                                    $newPaymentDetail->invoiceId = $paymentDetail['invoiceId'];
                                                    $newPaymentDetail->amount = $paymentDetail['amount'];
                                                    $newPaymentDetail->save();
                                                    
                                                    $paymentDetailIdsDone[] = $newPaymentDetail->id;
                                                }
                                            }
                                            
                                            // Delete deleted ones
                                            ExpensePaymentDetail::where('expensepayment', '=', $expense->id)->whereNotIn('id', $paymentDetailIdsDone)->delete();
                                        } else {
                                            // Remove them
                                            ExpensePaymentDetail::where('expensePayment', '=', $existingPayment->id)->delete();
                                        }
                                }
                            }
                        } else {
                            // Payments empty, so delete all payments and subpayments
                            ExpensePayment::where('expense', '=', $expense->id)->delete();
                        }
                        
                        
                        ////////////////////////
                        ///// Save products ////
                        ////////////////////////
                        if (array_key_exists('products', $expenseData)) {
                            $productIdsDone = [];
                            $products = $expenseData['products'];
                            
                            
                            foreach($products as $product) {
                                
                                $product['isAsset'] = (($product['isAsset'] == 'true') ? 1 : 0);
                                
                                if ($product['id'] == -1) {
                                    // New
                                    
                                    $newProduct = new ExpenseProduct;
                                    $newProduct->expense = $expense->id;
                                    $newProduct->productId = $product['productId'];
                                    $newProduct->productName = $product['productName'];
                                    $newProduct->lastPrice = $product['lastPrice'];
                                    $newProduct->purchasePrice = $product['purchasePrice'];
                                    $newProduct->salesPrice = $product['salesPrice'];
                                    $newProduct->quantity = $product['quantity'];
                                    $newProduct->discount = $product['discount'];
                                    $newProduct->isAsset = $product['isAsset'];
                                    $newProduct->save();
                                    
                                    $productIdsDone[] = $newProduct->id;
                                } else {
                                    // Existing
                                    
                                    $existingProduct = ExpenseProduct::find($product['id']);
                                    $existingProduct->productId = $product['productId'];
                                    $existingProduct->productName = $product['productName'];
                                    $existingProduct->lastPrice = $product['lastPrice'];
                                    $existingProduct->purchasePrice = $product['purchasePrice'];
                                    $existingProduct->salesPrice = $product['salesPrice'];
                                    $existingProduct->quantity = $product['quantity'];
                                    $existingProduct->discount = $product['discount'];
                                    $existingProduct->isAsset = $product['isAsset'];
                                    $existingProduct->save();
                                    
                                    $productIdsDone[] = $existingProduct->id;
                                }
                                
                            }
                            
                            ExpenseProduct::where('expense', '=', $expense->id)->whereNotIn('id', $productIdsDone)->delete();
                        } else {
                            ExpenseProduct::where('expense', '=', $expense->id)->delete();
                        }
                        
                        
                        
                        
                        
                } else {
                    // New expense. Create that bitch
                    
                    ////////////////////////
                    ///// Save expense /////
                    ////////////////////////
                    $saveExpense = json_decode(json_encode($expenseData));
                    
                    unset($saveExpense->payments);
                    unset($saveExpense->products);
                    unset($saveExpense->subtotals);
                    
                    $saveExpense->invoiceDate = date('Y-m-d', strtotime($saveExpense->invoiceDate));
                    $saveExpense->invoiceReceivedDate = date('Y-m-d', strtotime($saveExpense->invoiceReceivedDate));
                    $saveExpense->isOfficial = ($saveExpense->isOfficial == true) ? 1 : 0;
                    $saveExpense->isInternal = ($saveExpense->isInternal == true) ? 1 : 0;
                    $saveExpense->waitingForInvoice = ($saveExpense->waitingForInvoice == true) ? 1 : 0;
                    
                    // Check if the proforma already exists in another expense for the same supplier
                    if ($saveExpense->proforma != '') {
                        $proformaCheck = Expense::where('supplier', '=', $saveExpense->supplier)->where('proforma', '=', $saveExpense->proforma)->count();
                        if ($proformaCheck > 0)
                            return ['success' => false, 'message' => 'Could not save expense. An expense already exists for the same supplier with the same proforma number'];
                    }
                    
                    $expense = new Expense;
                    $expense->fill((array) $saveExpense);
                    $expense->save();
                    
                    
                    ////////////////////////
                    //// Save subtotals ////
                    ////////////////////////
                    
                    if (array_key_exists('subtotals', $expenseData)) {
                        $subtotals = $expenseData['subtotals'];
                        
                        foreach($subtotals as $subtotal) {
                            $newSubtotal = new ExpenseSubtotal;
                            $newSubtotal->expense = $expense['id'];
                            $newSubtotal->subtotal = $subtotal['subtotal'];
                            $newSubtotal->vat = $subtotal['vat'];
                            
                            $newSubtotal->save();
                        }
                    }
                    
                    ////////////////////////
                    ///// Save payments ////
                    ////////////////////////
                    
                    if (array_key_exists('payments', $expenseData)) {
                        $payments = $expenseData['payments'];
                        
                        foreach($payments as $payment) {
                            $newPayment = new ExpensePayment;
                            $newPayment->expense = $expense->id;
                            $newPayment->supplierId = $expense->supplier;
                            $newPayment->description = $payment['description'];
                            $newPayment->date = CommonFunctions::parseMaskedDateTime($payment['date']);
                            $newPayment->amount = $payment['amount'];
                            $newPayment->paymentMethod = $payment['paymentMethod'];
                            $newPayment->save();
                            
                            ////////////////////////
                            ///// Save details /////
                            ////////////////////////
                            
                            $paymentDetails = $payment['entries'];
                            
                            if (is_array($paymentDetails) && count($paymentDetails) > 0) {
                                foreach($paymentDetails as $paymentDetail) {
                                    $newDetail = new ExpensePaymentDetail;
                                    $newDetail->expensePayment = $newPayment->id;
                                    $newDetail->invoiceId = $paymentDetail['invoiceId'];
                                    $newDetail->amount = $paymentDetail['amount'];
                                    $newDetail->save();
                                }
                            }
                        }
                    }
                    
                    ////////////////////////
                    ///// Save poducts /////
                    ////////////////////////
                    
                    if (array_key_exists('products', $expenseData)) {
                        $products = $expenseData['products'];
                        
                        foreach($products as $product) {
                            
                            // Check if empty, if it is, continue
                            if ($product['productName'] == '' && $product['purchasePrice'] == '0')
                                continue;
                                
                                $product['isAsset'] = (($product['isAsset'] == 'true') ? 1 : 0);
                                
                                $newProduct = new ExpenseProduct;
                                $newProduct->expense = $expense->id;
                                $newProduct->productId = $product['productId'];
                                $newProduct->productName = $product['productName'];
                                $newProduct->lastPrice = $product['lastPrice'];
                                $newProduct->purchasePrice = $product['purchasePrice'];
                                $newProduct->salesPrice = $product['salesPrice'];
                                $newProduct->quantity = $product['quantity'];
                                $newProduct->discount = $product['discount'];
                                $newProduct->isAsset = $product['isAsset'];
                                $newProduct->save();
                        }
                    }
                }
                
                return array('success' => true);
                break;
                
            case 'create_new_expense_category':
                $category = new ExpenseCategory;
                $category->english = Request::get('english');
                $category->spanish = Request::get('spanish');
                $category->accountingCode = Request::get('accountingCode');
                $category->save();
                
                return ['success' => true, 'categoryId' => $category->id];
                break;
                
            case 'update_expense_category':
                $category = ExpenseCategory::find(Request::get('id'));
                
                $category->english = Request::get('english');
                $category->spanish = Request::get('spanish');
                $category->accountingCode = Request::get('accountingCode');
                $category->disabled = Request::get('disabled');
                $category->save();
                
                return ['success' => true];
                break;
                
            case 'create_new_expense_subcategory':
                $sub = new ExpenseSubcategory;
                $sub->english = Request::get('english');
                $sub->spanish = Request::get('spanish');
                
                // Get the max accounting code
                $lastAccountingCode = ExpenseSubCategory::where('category', '=', Request::get('category'))->max('accountingCode');
                $lastAccountingCode = substr($lastAccountingCode, strlen($lastAccountingCode) - 3);
                $newAccountingCode = (intval($lastAccountingCode) + 1);
                
                $sub->accountingCode = sprintf('%03d', $newAccountingCode);
                $sub->category = Request::get('category');
                $sub->save();
                
                return ['success' => true, 'categoryId' => $sub->id, 'accountingCode' => $sub->accountingCode];
                break;
                
            case 'update_expense_subcategory':
                $sub = ExpenseSubcategory::find(Request::get('id'));
                $sub->english = Request::get('english');
                $sub->spanish = Request::get('spanish');
                $sub->accountingCode = Request::get('accountingCode');
                $sub->category = Request::get('category');
                $sub->save();
                
                return ['success' => true];
                break;
                
            case 'delete_expense_subcategory':
                $sub = ExpenseSubcategory::find(Request::get('subCategoryId'));
                
                $count = Expense::where('subcategory', '=', $sub->id)->count();
                if ($count == 0) {
                    $sub->delete();
                    return ['success' => true];
                } else {
                    return ['success' => false, 'message' => 'An expense exists with this subcategory. Therefore you cannot delete it!'];
                }
                break;
                
            case 'expense_category_toggle_disabled':
                $category = ExpenseCategory::find(Request::get('categoryId'));
                $category->disabled = Request::get('disabled');
                $category->save();
                
                return ['success' => true];
                break;
                
            case 'delete_quote':
                if (!Auth::user()->hasPermission('delete_quote'))
                    return array('success' => false);
                    
                    $quote = Quote::find(Request::get('quoteId'));
                    
                    if (!$quote)
                        return array('success' => false);
                        
                        if (strtotime($quote->createdOn) < (time() - 7200))
                            return array('success' => false);
                            
                            // Delete quote entries
                            QuoteDetail::where('quoteId', '=', $quote->id)->delete();
                            
                            // Delete quote comments
                            QuoteComment::where('quoteId', '=', $quote->id)->delete();
                            
                            // Delete the quote itself
                            $quote->delete();
                            
                            return array('success' => true);
                            break;
                            
            case 'reminder_read':
                $reminder = Reminder::find(Request::get('reminderId'));
                
                if ($reminder->user != Auth::user()->id)
                    return array('success' => false);
                    
                    $reminder->read = 1;
                    $reminder->save();
                    
                    return array('success' => true);
                    break;
                    
            case 'get_support_popup':
                return ['success' => true, 'modal' => View::make('support/index')->render()];
                break;
                
            case 'save_support_question':
                // Send the question to FreshDesk
                // Backup your default mailer
                $backup = Mail::getSwiftMailer();
                
                // Setup your gmail mailer
                $transport = Swift_SmtpTransport::newInstance('mail.pepper-crm.net', 26);
                $transport->setUsername('internal-crm-support@pepper-crm.net');
                $transport->setPassword('903p7]pqfL=}');
                // Any other mailer configuration stuff needed...
                
                $gmail = new Swift_Mailer($transport);
                
                // Set the mailer as gmail
                Mail::setSwiftMailer($gmail);
                
                // Send your message
                $data = [
                    'user' => Auth::user(),
                    'question' => Request::get('question'),
                    'instance' => Instance::where('hash', '=', Settings::setting('installationId'))->first()
                ];
                Mail::send('emails.internalSupportEmail', $data, function($email) {
                    $email->from('internal-crm-support@pepper-crm.net', 'Pepper Support');
                    $email->to('support@peppercrm.freshdesk.com', 'Pepper Support');
                    $email->subject('Internal Support Question');
                    $email->replyTo(Auth::user()->companyEmail, Auth::user()->getFullname());
                });
                    
                    // Restore your original mailer
                    Mail::setSwiftMailer($backup);
                    
                    return array('success' => true);
                    break;
                    
                case 'save_support_feedback':
                    // Send the question to FreshDesk
                    // Backup your default mailer
                    $backup = Mail::getSwiftMailer();
                    
                    // Setup your gmail mailer
                    $transport = Swift_SmtpTransport::newInstance('mail.pepper-crm.net', 26);
                    $transport->setUsername('internal-crm-support@pepper-crm.net');
                    $transport->setPassword('903p7]pqfL=}');
                    // Any other mailer configuration stuff needed...
                    
                    $gmail = new Swift_Mailer($transport);
                    
                    // Set the mailer as gmail
                    Mail::setSwiftMailer($gmail);
                    
                    // Send your message
                    $data = [
                        'user' => Auth::user(),
                        'question' => Request::get('question'),
                        'instance' => Instance::where('hash', '=', Settings::setting('installationId'))->first(),
                        'type' => Request::get('type'),
                        'feedback' => Request::get('feedback')
                    ];
                    Mail::send('emails.internalFeedbackEmail', $data, function($email) {
                        $email->from('internal-crm-support@pepper-crm.net', 'Pepper Support');
                        $email->to('support@peppercrm.freshdesk.com', 'Pepper Support');
                        $email->subject('Internal Feedback');
                        $email->replyTo(Auth::user()->companyEmail, Auth::user()->getFullname());
                    });
                        
                        // Restore your original mailer
                        Mail::setSwiftMailer($backup);
                        
                        return array('success' => true);
                        
                        break;
                        
                    case 'get_quick_sell':
                        $returnArray = [];
                        
                        $returnArray['adTypes'] = AdType::where('discontinued', '=', 0)->orderBy('id', 'ASC')->get()->pluck('type', 'id');
                        $returnArray['vats'] = VAT::pluck('type', 'id');
                        $returnArray['vatValues'] = VAT::pluck('value', 'id');
                        
                        return $returnArray;
                        break;
                        
                    case 'create_quick_sale':
                        
                        // First, we create the quote
                        $installationId = Settings::setting('installationId');
                        if ($installationId == 'whitedesigncoY7rp')
                            $customer = Customer::find(2199);
                            else if ($installationId == 'ebox8374')
                                $customer = Customer::find(6206);
                                else {
                                    $customer = Customer::where('companyName', '=', 'General Public')->first();
                                    if (!$customer) {
                                        return ['success' => false, 'message' => 'An error occurred. Please try again'];
                                    }
                                }
                                
                                $quoteData = json_decode(json_encode(Request::get('quoteData')));
                                
                                // Create new quote row
                                $quote = new Quote();
                                $quote->customer = $customer->id;
                                $quote->createdBy = Auth::user()->id;
                                $quote->assignedTo = Auth::user()->id;
                                $quote->createdOn = date('Y-m-d H:i:s');
                                $quote->startedOn = CommonFunctions::getMysqlDate();
                                $quote->requiredBy = CommonFunctions::getMysqlDate();
                                $quote->completedOn = CommonFunctions::getMysqlDate();
                                $quote->status = 6; // TODO Make this a variable
                                $quote->adType = $quoteData->adType;
                                $quote->vat = $quoteData->vat;
                                $quote->supCosts = $quoteData->supCosts;
                                $quote->save();
                                
                                // Add customer history item
                                $item = new ContactHistory;
                                $item->customer = $customer->id;
                                $item->placedOn = CommonFunctions::getMysqlDate();
                                $item->placedBy = Auth::user()->id;
                                $item->message = 'Quote #' . $quote->id . ' created by ' . Auth::user()->getFullname() . ' through the quick-sale module.';
                                $item->save();
                                
                                // Add the entries to the quote
                                $entries = (Request::get('entries')) ? Request::get('entries') : array();
                                foreach($entries as $entry) {
                                    unset($entry['$$hashKey']);
                                    
                                    $newEntry = new QuoteDetail;
                                    $newEntry->fill($entry);
                                    $newEntry->quoteId = $quote->id;
                                    $newEntry->productName = Product::find($entry['productId'])->name;
                                    $newEntry->save();
                                }
                                
                                /*unset($quoteData['total']);
                                 unset($quoteData['subtotal']);
                                 unset($quoteData['customerName']);
                                 unset($quoteData['statusText']);*/
                                
                                $quote->save();
                                
                                // Create the payment for the quote
                                $payment = new Payment;
                                $payment->date = CommonFunctions::getMysqlDate();
                                $payment->paymentType = Request::get('paymentData')['paymentMethod'];
                                $payment->notes = Request::get('paymentData')['notes'];
                                $payment->nonCash = Request::get('paymentData')['nonCashTotal'];
                                $payment->customerId = $customer->id;
                                
                                if (is_array(Request::get('paymentNotes')) && count(Request::get('paymentNotes')) > 0) {
                                    foreach(Request::get('paymentNotes') as $note => $amount) {
                                        $payment['n' . $note] = $amount;
                                    }
                                }
                                
                                if (is_array(Request::get('paymentCoins')) && count(Request::get('paymentCoins')) > 0) {
                                    foreach(Request::get('paymentCoins') as $coin => $amount) {
                                        $payment['c' . $coin] = $amount;
                                    }
                                }
                                
                                $payment->outToBank = 0;
                                $payment->checkedByManagement = 0;
                                $payment->createdBy = Auth::id();
                                $payment->save();
                                
                                $paymentDetail = new PaymentDetail;
                                $paymentDetail->date = CommonFunctions::getMysqlDate();
                                $paymentDetail->amount = $payment->getTotal();
                                $paymentDetail->quoteId = $quote->id;
                                $paymentDetail->paymentId = $payment->id;
                                $paymentDetail->save();
                                
                                return ['success' => true, 'quoteId' => $quote->id];
                                break;
                                
                    case 'get_media_library_popup':
                        return ['success' => true, 'modal' => View::make('mediagallery')->render()];
                        break;
                        
                    case 'media_library_upload':
                        // Create the media item
                        $item = new MediaItem;
                        
                        $item->filename = Requestfile('file')->getClientOriginalName();
                        $item->size = Requestfile('file')->getSize();
                        
                        while(true) {
                            $hash = CommonFunctions::generateRandomString(16);
                            
                            $hashCheck = MediaItem::where('hash', '=', $hash)->count();
                            
                            if ($hashCheck == 0)
                                break;
                        }
                        
                        $item->hash = $hash;
                        $item->name = pathinfo($item->filename, PATHINFO_FILENAME);
                        $item->description = '';
                        $item->createdBy = Auth::id();
                        $item->createdAt = CommonFunctions::getMysqlDate();
                        $item->category = '';
                        $item->save();
                        
                        // Check if the media folder exists in the storage folder
                        if (!File::exists(storage_path() . '/files/' . Settings::setting('installationId') . '/media')) {
                            mkdir(storage_path() . '/files/' . Settings::setting('installationId') . '');
                            mkdir(storage_path() . '/files/' . Settings::setting('installationId') . '/media');
                        }
                        
                        // Now, we are going to create a thumbnail if the file is an image...
                        if (in_array(strtolower(Requestfile('file')->getClientOriginalExtension()), ['jpg', 'png', 'gif', 'bmp'])) {
                            // Move the file
                            Requestfile('file')->move(storage_path() . '/files/' . Settings::setting('installationId') . '/media', $item->name . '_' . $item->hash . '.' . Requestfile('file')->getClientOriginalExtension());
                            
                            // Resize the image
                            $image = new SimpleImage;
                            $image->load($item->getPath());
                            $image->best_fit(1920, 1920);
                            $image->save();
                            
                            $image = new SimpleImage;
                            $image->load($item->getPath());
                            $image->thumbnail(100, 100);
                            $image->save($item->getThumbnailPath());
                        }
                        
                        return ['success' => true, 'item' => $item];
                        break;
                        
                    case 'delete_media_item':
                        $item = MediaItem::find(Request::get('itemId'));
                        
                        if (!$item)
                            return ['success' => false, 'message' => 'The media item could not be found'];
                            
                            $item->deletedBy = Auth::id();
                            $item->deletedAt = CommonFunctions::getMysqlDate();
                            $item->save();
                            
                            return ['success' => true];
                            break;
                            
                    case 'update_media_item':
                        $item = Request::get('item');
                        $dbItem = MediaItem::find($item['id']);
                        
                        if (!$dbItem)
                            return ['success' => false, 'message' => 'Could not find item'];
                            
                            $dbItem->name = $item['name'];
                            $dbItem->description = $item['description'];
                            $dbItem->save();
                            
                            return ['success' => true];
                            break;
                            
                    case 'save_quote_description_sheet':
                        
                        $quote = Quote::find(Request::get('quoteId'));
                        
                        if (!$quote)
                            return ['success' => false, 'message' => 'Quote could not be found'];
                            
                            $rows = json_decode(json_encode(Request::get('rows')));
                            
                            $rowIdsDone = [];
                            $position = 0;
                            
                            if (count($rows) > 0) {
                                foreach($rows as $row) {
                                    if (property_exists($row, 'id')) {
                                        // Existing row
                                        $dbRow = QuoteDescriptionRow::find($row->id);
                                        
                                        if ($dbRow) {
                                            $dbRow->title = $row->title;
                                            $dbRow->description = $row->description;
                                            
                                            if (property_exists($row, 'image1') && property_exists($row->image1, 'id'))
                                                $dbRow->image1 = $row->image1->id;
                                                else
                                                    $dbRow->image1 = '';
                                                    
                                                    if (property_exists($row, 'image2') && property_exists($row->image2, 'id'))
                                                        $dbRow->image2 = $row->image2->id;
                                                        else
                                                            $dbRow->image2 = '';
                                                            
                                                            $dbRow->position = $position;
                                                            $dbRow->save();
                                                            
                                                            $rowIdsDone[] = $dbRow->id;
                                        }
                                    } else {
                                        // New row
                                        $dbRow = new QuoteDescriptionRow;
                                        $dbRow->quoteId = $quote->id;
                                        
                                        $dbRow->title = $row->title;
                                        $dbRow->description = $row->description;
                                        
                                        if (property_exists($row, 'image1') && property_exists($row->image1, 'id'))
                                            $dbRow->image1 = $row->image1->id;
                                            else
                                                $dbRow->image1 = '';
                                                
                                                if (property_exists($row, 'image2') && property_exists($row->image2, 'id'))
                                                    $dbRow->image2 = $row->image2->id;
                                                    else
                                                        $dbRow->image2 = '';
                                                        
                                                        $dbRow->position = $position;
                                                        $dbRow->save();
                                                        
                                                        $rowIdsDone[] = $dbRow->id;
                                    }
                                    $position++;
                                }
                            }
                            
                            // Delete deleted rows
                            foreach(QuoteDescriptionRow::where('quoteId', '=', $quote->id)->whereNotIn('id', $rowIdsDone)->get() as $row) {
                                $row->delete();
                            }
                            
                            return ['success' => true];
                            
                            break;
                            
                    case 'get_quote_email':
                        $email = QuoteEmail::find(Request::get('emailId'));
                        
                        if (!$email)
                            return ['success' => false, 'message' => 'Email could not be found'];
                            
                            $attachments = $email->attachments;
                            
                            if ($attachments->count() === 1) {
                                return [
                                    'email' => $email,
                                    'attachment' => $attachments->first()
                                ];
                            } else {
                                return ['email' => $email];
                            }
                            break;
                            
                    case 'get_invoice_email':
                        $email = InvoiceEmail::find(Request::get('emailId'));
                        
                        if (!$email)
                            return ['success' => false, 'message' => 'Email could not be found'];
                            
                            $attachments = $email->attachments;
                            
                            if ($attachments->count() === 1) {
                                return [
                                    'email' => $email,
                                    'attachment' => $attachments->first()
                                ];
                            } else {
                                return ['email' => $email];
                            }
                            break;
                            
                    case 'save_new_system_variable':
                        $variable = json_decode(json_encode(Request::get('variable')));
                        switch(Request::get('category')) {
                            case 'Advertisement Types':
                                $adType = new AdType;
                                $adType->type = $variable->type;
                                $adType->discontinued = 0;
                                $adType->save();
                                
                                return ['success' => true, 'varId' => $adType->id];
                                break;
                            case 'Product Categories':
                                $category = new ProductCategory;
                                $category->type = $variable->type;
                                $category->discontinued = 0;
                                $category->save();
                                
                                return ['success' => true, 'varId' => $category->id];
                                break;
                            case 'Customer Credit Ratings':
                                $rating = new CustomerCreditRating;
                                $rating->type = $variable->type;
                                $rating->discontinued = 0;
                                $rating->abbreviation = $variable->abbreviation;
                                $rating->save();
                                
                                return ['success' => true, 'varId' => $rating->id];
                                break;
                            case 'Job Statusses':
                                $jobStatus = new JobStatus;
                                $jobStatus->type = $variable->type;
                                $jobStatus->save();
                                
                                return ['success' => true, 'varId' => $jobStatus->id];
                                break;
                            case 'Payment Methods':
                                $method = new PayMethod;
                                $method->type = $variable->type;
                                $method->commission = $variable->commission;
                                $method->save();
                                
                                return ['success' => true, 'varId' => $method->id];
                                break;
                            case 'Call Out Fees':
                                $fee = new CallOutFee;
                                $fee->type = $variable->type;
                                $fee->price = $variable->price;
                                $fee->save();
                                
                                return ['success' => true, 'varId' => $fee->id];
                                break;
                            case 'VAT values':
                                $vat = new VAT;
                                $vat->type = $variable->type;
                                $vat->description = $variable->description;
                                $vat->value = $variable->value;
                                $vat->save();
                                
                                return ['success' => true, 'varId' => $vat->id];
                                break;
                            case 'Company Roles':
                                $role = new CompanyRole;
                                $role->type = $variable->type;
                                $role->save();
                                
                                return ['success' => true, 'varId' => $role->id];
                                break;
                            case 'Payment Terms':
                                $term = new PaymentTerm;
                                $term->type = $variable->type;
                                $term->save();
                                
                                return ['success' => true, 'varId' => $term->id];
                                break;
                            case 'Customer Types':
                                $type = new CustomerType;
                                $type->type = $variable->type;
                                $type->save();

				                return ['success' => true, 'varId' => $type->id];
				            break;
                            case 'Customer Sectors':
                                $type = new Sector;
                                $type->type = $variable->type;
                                $type->save();

                                return ['success' => true, 'varId' => $type->id];
                            break;
                        }
                    break;
                        
                    case 'save_existing_system_variable':
                        $variable = json_decode(json_encode(Request::get('variable')));
                        
                        switch(Request::get('category')) {
                            case 'Advertisement Types':
                                $adType = AdType::find($variable->id);
                                $adType->type = $variable->type;
                                $adType->discontinued = 0;
                                $adType->save();
                                
                                return ['success' => true, 'varId' => $adType->id];
                                break;
                            case 'Product Categories':
                                $category = ProductCategory::find($variable->id);
                                $category->type = $variable->type;
                                $category->discontinued = 0;
                                $category->save();
                                
                                return ['success' => true, 'varId' => $category->id];
                                break;
                            case 'Customer Credit Ratings':
                                $rating = CustomerCreditRating::find($variable->id);
                                $rating->type = $variable->type;
                                $rating->discontinued = 0;
                                $rating->abbreviation = $variable->abbreviation;
                                $rating->save();
                                
                                return ['success' => true, 'varId' => $rating->id];
                                break;
                            case 'Job Statusses':
                                $jobStatus = JobStatus::find($variable->id);
                                $jobStatus->type = $variable->type;
                                $jobStatus->save();
                                
                                return ['success' => true, 'varId' => $jobStatus->id];
                                break;
                            case 'Payment Methods':
                                $method = PayMethod::find($variable->id);
                                $method->type = $variable->type;
                                $method->commission = $variable->commission;
                                $method->save();
                                
                                return ['success' => true, 'varId' => $method->id];
                                break;
                            case 'Call Out Fees':
                                $fee = CallOutFee::find($variable->id);
                                $fee->type = $variable->type;
                                $fee->price = $variable->price;
                                $fee->save();
                                
                                return ['success' => true, 'varId' => $fee->id];
                                break;
                            case 'VAT values':
                                $vat = VAT::find($variable->id);
                                $vat->type = $variable->type;
                                $vat->description = $variable->description;
                                $vat->value = $variable->value;
                                $vat->save();
                                
                                return ['success' => true, 'varId' => $vat->id];
                                break;
                            case 'Company Roles':
                                $role = CompanyRole::find($variable->id);
                                $role->type = $variable->type;
                                $role->save();
                                
                                return ['success' => true, 'varId' => $role->id];
                                break;
                            case 'Payment Terms':
                                $term = PaymentTerm::find($variable->id);
                                $term->type = $variable->type;
                                $term->save();
                                
                                return ['success' => true, 'varId' => $term->id];
                            break;
                            case 'Customer Sectors':
                                $term = Sector::find($variable->id);
                                $term->type = $variable->type;
                                $term->save();

                                return ['success' => true, 'varId' => $term->id];
                            break;
                        }
                        break;
                        
                    case 'get_product_last_sales_price':
                        $expenseIds = Expense::where('supplier', '=', Request::get('supplierId'))->pluck('id');
                        
                        if (!is_array($expenseIds) || count($expenseIds) == 0)
                            return ['success' => true, 'lastPrice' => '-'];
                            
                            $expenseDetailsForSupplier = ExpenseProduct::whereIn('expense', $expenseIds);
                            
                            $lastProduct = $expenseDetailsForSupplier->where('productId', '=', Request::get('productId'))->orderBy('id', 'DESC')->first();
                            
                            if ($lastProduct) {
                                return ['success' => true, 'lastPrice' => $lastProduct->purchasePrice];
                            } else {
                                return ['success' => true, 'lastPrice' => '-'];
                            }
                            break;
                            
                    case 'create_customer_exists_check':
                        
                        $phone = (Request::get('phone') == '') ? 'abcdef3ghiajgujher' : Request::get('phone'); // Gotta love hacky hacks!
                        $mobile = (Request::get('mobile') == '') ? 'abcdefghia2jgujher' : Request::get('mobile'); // Gotta love hacky hacks!
                        $email = (Request::get('email') == '') ? 'abcd3efghia5jgujher' : Request::get('email'); // Gotta love hacky hacks!
                        
                        $customers = Customer::where('phone', '=', $phone)->orWhere('mobile', '=', $mobile)->orWhere('email', '=', $email)->get();
                        
                        if ($customers->count() == 0)
                            return ['exists' => false];
                            else {
                                return ['exists' => true, 'details' => $customers];
                            }
                            
                            break;
                            
                    case 'invoice_change_customer':
                        $invoice = Invoice::find(Request::get('invoiceId'));
                        
                        if (!$invoice)
                            return ['success' => false, 'message' => 'Invoice not found'];
                            
                            $customer = Customer::find(Request::get('customerId'));
                            
                            if (!$customer)
                                return ['success' => false, 'message' => 'Customer not found'];
                                
                                $oldCustomer = $invoice->customer;
                                
                                $invoice->customer = Request::get('customerId');
                                $invoice->save();
                                
                                LogManager::log("Changed the customer of invoice #" . $invoice->id . " from #" . $oldCustomer . " to #" . $invoice->customer);
                                
                                return ['success' => true];
                                break;
                                
                    case 'quote_change_customer':
                        $quote = Quote::find(Request::get('quoteId'));
                        
                        if (!$quote)
                            return ['success' => false, 'message' => 'Quote not found'];
                            
                            $customer = Customer::find(Request::get('customerId'));
                            
                            if (!$customer)
                                return ['success' => false, 'message' => 'Customer not found'];
                                
                                if ($quote->getPaid() != 0)
                                    return ['success' => false, 'message' => 'Could not change quote customer: payments for this quote already exist!'];
                                    
                                    $oldCustomer = $quote->customer;
                                    
                                    $quote->customer = Request::get('customerId');
                                    $quote->save();
                                    
                                    LogManager::log("Changed the customer of quote #" . $quote->id . " from #" . $oldCustomer . " to #" . $quote->customer);
                                    
                                    return ['success' => true];
                                    break;
                                    
                    case 'delete_customer_file':
                        $customer = Customer::find(Request::get('customerId'));
                        
                        if (!$customer)
                            return ['success' => false];
                            
                            $customerFile = CustomerFile::where('customer', '=', $customer->id)->where('hash', '=', Request::get('fileHash'))->first();
                            
                            if (!$customerFile)
                                return ['success' => false];
                                
                                @unlink(storage_path() . '/files/' . Settings::setting('installationId') . '/customer_files/' . $customer->id . '/' . $customerFile->hash . '.' . pathinfo($customerFile->filename, PATHINFO_EXTENSION));
                                $customerFile->delete();
                                
                                return ['success' => true];
                                break;
                                
                    case 'customer_file_save_description':
                        $customer = Customer::find(Request::get('customerId'));
                        $file = CustomerFile::find(Request::get('fileId'));
                        
                        if (!$customer || !$file)
                            return ['success' => false];
                            
                            $file->description = Request::get('description');
                            $file->save();
                            
                            return ['success' => true];
                            break;
                            
                    case 'create_credit_quote_from_quote':
                        $quote = Quote::find(Request::get('quoteId'));
                        
                        if (!$quote)
                            return ['success' => false];
                            
                            // Create the new quote
                            $newQuote = new Quote;
                            $newQuote->createdOn = CommonFunctions::getMysqlDate();
                            $newQuote->customer = $quote->customer;
                            $newQuote->description = 'Credit quote for quote #' . $quote->id;
                            
                            $jobStatus = JobStatus::where('type', 'LIKE', '%in progress%')->first();
                            if ($jobStatus)
                                $jobStatusId = $jobStatus->id;
                                else
                                    $jobStatusId = JobStatus::min('id');
                                    
                                    $newQuote->status = $jobStatusId;
                                    $newQuote->createdBy = Auth::user()->id;
                                    $newQuote->assignedTo = Auth::user()->id;
                                    $newQuote->startedOn = CommonFunctions::getMysqlDate();
                                    $newQuote->completedOn = CommonFunctions::getMysqlDate();
                                    $newQuote->adType = Settings::setting('defaultAdType');
                                    $newQuote->vat = Settings::setting('defaultVat');
                                    $newQuote->supCosts = ($quote->supCosts * (-1));
                                    $newQuote->startedOn = CommonFunctions::getMysqlDate();
                                    $newQuote->save();
                                    
                                    // Loop over the quote details, and save them for the new credit quote
                                    foreach($quote->getEntries as $entry) {
                                        
                                        $newEntry = new QuoteDetail;
                                        $newEntry->quoteId = $newQuote->id;
                                        $newEntry->productId = $entry->productId;
                                        $newEntry->productName = $entry->productName;
                                        $newEntry->purchasePrice = $entry->purchasePrice;
                                        $newEntry->unitPrice = $entry->unitPrice;
                                        $newEntry->quantity = ($entry->quantity * (-1));
                                        $newEntry->discount = $entry->discount;
                                        $newEntry->description = $entry->description;
                                        $newEntry->save();
                                    }
                                    
                                    // Return success and the newly created quote's ID
                                    return ['success' => true, 'quoteId' => $newQuote->id];
                                    
                                    break;
                                    
                    case 'new_vat_confirm':
                        $confirm = new VATConfirm;
                        $confirm->customer = Request::get('customer');
                        $confirm->user = Auth::id();
                        $confirm->text = Request::get('text');
                        $confirm->save();
                        
                        return ['success' => true];
                        break;
                        
                    case 'new_customer_address':
                        $address = new Address;
                        $address->customer = Request::get('customer');
                        $address->label = Request::get('label');
                        $address->address = Request::get('address');
                        $address->city = Request::get('city');
                        $address->postalcode = Request::get('postalcode');
                        $address->province = Request::get('province');
                        $address->country = Request::get('country');
                        $address->telephone = Request::get('telephone');
                        $address->email = Request::get('email');
                        $address->save();
                        
                        return ['success' => true];
                        break;
                        
                    case 'delete_customer_address':
                        $address = Address::find(Request::get('addressId'));
                        $address->delete();
                        
                        return ['success' => true];
                        break;

            case 'segmentation':
                $returnArray = ['Test1'];
                break;
        }
        echo json_encode($returnArray);
    }
}
