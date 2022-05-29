<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\variables\CustomerCreditRating;

class Customer extends Model {
	protected $table = 'customers';

	protected $guarded = array('id');

	public function sector() {
		return $this->hasOne('Sector', 'id', 'sector');
	}

	public function getCustomerCreditRating() {
		return $this->hasOne(CustomerCreditRating::class, 'id', 'credit');
	}
	
	public function getContactPerson() {
		return $this->contactTitle . ' ' . $this->contactName;
	}

	public function getAdType() {
		return $this->hasOne(AdType::class, 'id', 'advertisingType');
	}

	public function getCreator() {
		return $this->hasOne(User::class, 'id', 'createdBy');
	}

	public function getContactHistory() {
		return $this->hasMany(ContactHistory::class, 'customer', 'id')->orderBy('placedOn', 'desc');
	}

	public function getQuotes() {
		return $this->hasMany('Quote', 'customer', 'id')->orderBy('createdOn', 'desc');
	}

	public function getInvoices() {
		return $this->hasMany('Invoice', 'customer', 'id')->orderBy('createdOn', 'desc');
	}

	public function getCreditnotes() {
		return $this->hasMany('Creditnote', 'customer', 'id')->orderBy('createdOn', 'desc');
	}

	public function getPayments() {
		return $this->hasMany(Payment::class, 'customerId', 'id');
	}

	public function getFiles() {
		return $this->hasMany('CustomerFile', 'customer', 'id')->orderBy('addedOn', 'DESC');
	}

	public function getPaymentTerms() {
		return $this->hasOne('PaymentTerm', 'id', 'paymentTerms');
	}

	public function getCurrency() {
		return $this->hasOne('Currency', 'id', 'currency');
	}

	public function getType() {
		return $this->hasOne('CustomerType', 'id', 'type');
	}

	public function vatConfirms() {
		return $this->hasMany('VATConfirm', 'customer', 'id');
	}

	public function addresses() {
		return $this->hasMany('Address', 'customer', 'id');
	}

	public function getPaymentDetails() {
		$quotes = Quote::where('customer', '=', $this->id)->get();
		if (empty($quotes))
			return [];

		if (Quote::where('customer', '=', $this->id)->count() == 0)
			return array();

		return PaymentDetail::whereIn('quoteId', Quote::where('customer', '=', $this->id)->pluck('id'))->orderBy('date', 'DESC')->get();
	}

	public function getRenewals() {
		return $this->hasMany('Renewal', 'customer', 'id')->orderBy('cancelled', 'ASC')->orderBy('nextRenewalDate', 'asc');
	}
	
	// Get the customer name (if companyname = empty, show contact person)
	public function getCustomerName() {
		if ($this->companyName == '') {
			return $this->contactName;
		} else {
			return $this->companyName;
		}
	}

	public function getQuoteCount() {
		return $this->getQuotes->count();
	}

	public function getInvoiceCount() {
		return $this->getInvoices->count();
	}

	public function getCreditnoteCount() {
		return $this->getCreditnotes()->count();
	}

	public function getUnpaidInvoiceCount() {
		return 0;
	}

	public function getFullAddress() {
		$address = '<b>' . $this->getCustomerName() . '</b><br>';
		if ($this->address != '')
			$address .= $this->address . '<br>';

		if ($this->postalCode != '')
			$address .= $this->postalCode;

		if ($this->city != '' && $this->postalCode != '')
			$address .= ', ' . $this->city . '<br>';
		if ($this->city != '' && $this->postalCode == '')
			$address .= $this->city . '<br>';

		if ($this->region != '')
			$address .= $this->region;

		if ($this->country != '' && $this->region != '')
			$address .= ', ' . $this->country;
		if ($this->country != '' && $this->region == '')
			$address .= $this->country;

		if ($this->phone != '')
			$address .= '<br>' . $this->phone;

		if ($this->mobile != '')
			$address .= ' (M ' . $this->mobile . ')';

		return $address;
	}

	public function hasCoordinates() {
		return ($this->locationLat != null && $this->locationLng != null);
	}
	
}