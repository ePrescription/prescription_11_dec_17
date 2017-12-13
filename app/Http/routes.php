<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

Route::get('/', function () {
    return view('portal.login');
});

Route::get('/login', function () {
    return view('portal.login');
});

Route::post('/dologin', array('as' => 'user.dologin', 'uses' => 'Doctor\DoctorController@userlogin'));

Route::any('doctor/login', array('as' => 'doctor.doctorloginform', 'uses' => 'Doctor\DoctorController@doctorloginform'));
Route::post('doctor/dologin', array('as' => 'doctor.dologin', 'uses' => 'Doctor\DoctorController@doctorlogin'));

Route::get('/dashboard', function () {
    return view('portal.index');
});


Route::get('/logout', function() {
    Auth::logout();
    Session::flush();
    return Redirect::to('/');
});

/* Route::group(array('prefix' => 'hospital', 'namespace' => 'Common'), function()
  {
  Route::get('rest/api/get/hospitals', array('as' => 'common.hospitals', 'uses' => 'CommonController@getHospitalByKeyword'));
  Route::get('rest/api/{patientId}/profile', array('as' => 'patient.profile', 'uses' => 'CommonController@getPatientProfile'));
  }); */

Route::group(['prefix' => 'api'], function() {
    Route::post('token', 'AuthenticateController@authenticateUser');
    /* Route::group(['middleware' => 'jwt-auth'], function () {
      Route::post('getuserdetails', 'AuthenticateController@getUserDetails');
      }); */
});

Route::group(['prefix' => 'hospital'], function() {
    Route::group(['namespace' => 'Common'], function() {
        Route::get('rest/api/get/hospitals', array('as' => 'common.hospitals', 'uses' => 'CommonController@getHospitalByKeyword'));
        //Route::get('rest/api/{patientId}/profile', array('as' => 'patient.profile', 'uses' => 'CommonController@getPatientProfile'));
    });
});

Route::group(array('prefix' => 'patient'), function() {
    Route::get('{id}/dashboard', function () {
        return view('portal.patient-dashboard');
    });

    Route::group(['namespace' => 'Common'], function() {
        Route::get('rest/api/{patientId}/profile', array('as' => 'patient.profile', 'uses' => 'CommonController@getPatientProfile'));
        Route::get('rest/api/details', array('as' => 'patient.patient', 'uses' => 'CommonController@searchPatientByPid'));
    });

    Route::group(['namespace' => 'Lab'], function() {
        Route::get('rest/api/{patientId}/labtests', array('as' => 'patient.labtests', 'uses' => 'LabController@getTestsForLabForPatient'));
        Route::get('rest/api/labtests', array('as' => 'patient.lid', 'uses' => 'LabController@getLabTestsByLidForPatient'));
        Route::get('rest/api/lab/{labId}', array('as' => 'patient.labdetails', 'uses' => 'LabController@getLabTestDetailsForPatient'));
    });

    Route::group(['namespace' => 'Pharmacy'], function() {
        Route::get('rest/api/{patientId}/prescriptions', array('as' => 'patient.prescriptions', 'uses' => 'PharmacyController@getPrescriptionListForPatient'));
        Route::get('rest/api/prescription', array('as' => 'patient.searchbyprid', 'uses' => 'PharmacyController@getPrescriptionByPridForPatient'));
        Route::get('rest/api/prescription/{prescriptionId}', array('as' => 'patient.prescriptiondetails', 'uses' => 'PharmacyController@getPrescriptionDetailsForPatient'));
    });

    // Move all the web calls here

    Route::group(['namespace' => 'Doctor'], function() {
        Route::get('rest/{patientId}/details', array('as' => 'patient.details', 'uses' => 'DoctorController@getPatientAllDetails'));
        Route::get('rest/{patientId}/appointments', array('as' => 'patient.appointments', 'uses' => 'DoctorController@getPatientAppointments'));
        Route::get('rest/api/{patientId}/feereceipts', array('as' => 'patient.feereceipts', 'uses' => 'DoctorController@getFeeReceiptsByPatient'));
    });
});


Route::group(array('prefix' => 'fronthospital', 'namespace' => 'Lab'), function() {
    //Route::get('rest/api/{patientId}/labtests', array('as' => 'patient.labtests', 'uses' => 'LabController@getTestsForLabForPatient'));
    //Route::get('rest/api/labtests', array('as' => 'patient.lid', 'uses' => 'LabController@getLabTestsByLidForPatient'));
    Route::get('rest/api/lab/{labId}', array('as' => 'patient.labdetails', 'uses' => 'LabController@getLabTestDetailsForHospital'));

    Route::get('rest/api/{hid}/patient/{patientId}/lab-report-download', array('as' => 'lab.getlabtestreports', 'uses' => 'LabController@getPatientDocumentsForHospital'));
});

Route::group(array('prefix' => 'fronthospital', 'namespace' => 'Pharmacy'), function() {
    //Route::get('rest/api/{patientId}/prescriptions', array('as' => 'patient.prescriptions', 'uses' => 'PharmacyController@getPrescriptionListForPatient'));
    //Route::get('rest/api/prescription', array('as' => 'patient.searchbyprid', 'uses' => 'PharmacyController@getPrescriptionByPridForPatient'));
    Route::get('rest/api/prescription/{prescriptionId}', array('as' => 'patient.prescriptiondetails', 'uses' => 'PharmacyController@getPrescriptionDetailsForHospital'));
});


Route::group(array('prefix' => 'fronthospital', 'namespace' => 'Doctor'), function() {
    /*
      Route::get('{id}/dashboard', function ($id) {
      //return redirect('fronthospital/rest/api/'.$id.'/profile');
      return view('portal.hospital-dashboard');
      });
     */


    Route::get('{id}/dashboard', array('as' => 'patient.dashboard', 'uses' => 'DoctorController@getDashboardDetailsForFront'));
    Route::get('{id}/doctor/{doctorId}/dashboard', array('as' => 'doctor.dashboard', 'uses' => 'DoctorController@getDashboardDetailsForDoctor'));

    Route::get('{hospitalId}/futureappointments', array('as' => 'hospital.futureappointments', 'uses' => 'DoctorController@getFutureAppointmentsForDashboard'));


    Route::get('rest/{hospitalId}/patients/appointments', array('as' => 'patient.appointmentcategory', 'uses' => 'DoctorController@getPatientsByAppointmentCategoryForFront'));

    Route::get('patients/appointments/{appointmentId}/details', array('as' => 'patient.appointmentdetails', 'uses' => 'DoctorController@getAppointmentDetails'));

    Route::get('patients/appointments/{appointmentId}/cancelappointment', array('as' => 'patient.cancelappointment', 'uses' => 'DoctorController@cancelAppointmentForFront'));
    Route::get('patients/appointments/{appointmentId}/transferappointment', array('as' => 'patient.transferappointment', 'uses' => 'DoctorController@transferAppointmentForFront'));
    Route::get('rest/api/transferappointment', array('as' => 'patient.transferappointment', 'uses' => 'DoctorController@transferAppointmentForFront'));


    Route::get('rest/api/{hospitalId}/patientnames', array('as' => 'hospital.searchnames', 'uses' => 'DoctorController@getPatientNamesForHospital'));
    Route::get('rest/api/{patientId}/details', array('as' => 'hospital.details', 'uses' => 'DoctorController@getPatientDetailsById'));

    Route::get('rest/api/{specialtyId}/referraldoctors', array('as' => 'hospital.referraldoctors', 'uses' => 'DoctorController@getDoctorsBySpecialty'));


    Route::get('rest/api/{hospitalId}/patients', array('as' => 'hospital.patients', 'uses' => 'DoctorController@getPatientsByHospitalForFront'));


    Route::get('rest/api/{hospitalId}/addpatient', array('as' => 'hospital.addpatient', 'uses' => 'DoctorController@addPatientsByHospitalForFront'));
    Route::post('rest/api/{hospitalId}/savepatient', array('as' => 'hospital.savepatient', 'uses' => 'DoctorController@savePatientsByHospitalForFront'));

    Route::get('rest/api/{hospitalId}/addpatientwithappointment', array('as' => 'hospital.addpatientwithappointment', 'uses' => 'DoctorController@addPatientWithAppointmentByHospitalForFront'));
    Route::post('rest/api/{hospitalId}/savepatientwithappointment', array('as' => 'hospital.savepatientwithappointment', 'uses' => 'DoctorController@savePatientWithAppointmentByHospitalForFront'));

    Route::post('rest/api/referraldoctor', array('as' => 'specialties.savereferraldoctor', 'uses' => 'DoctorController@saveReferralDoctor'));
    Route::get('rest/api/appointmenttimes', array('as' => 'hospital.appointmenttimes', 'uses' => 'DoctorController@getAppointmentTimes'));

    Route::get('rest/api/{hospitalId}/patient/{patientId}/addappointment', array('as' => 'hospital.addappointment', 'uses' => 'DoctorController@addAppointmentByHospitalForFront'));
    Route::post('rest/api/{hospitalId}/patient/{patientId}/saveappointment', array('as' => 'hospital.saveappointment', 'uses' => 'DoctorController@saveAppointmentByHospitalForFront'));
    Route::get('rest/api/{hospitalId}/patient/{patientId}/completeappointment', array('as' => 'hospital.completeappointment', 'uses' => 'DoctorController@completeAppointmentByHospitalForFront'));

    Route::get('rest/api/{hospitalId}/patient/{patientId}/details', array('as' => 'hospital.patientdetails', 'uses' => 'DoctorController@PatientDetailsByHospitalForFront'));


    Route::get('rest/api/{hospitalId}/patient/{patientId}/edit', array('as' => 'hospital.patientdetailsedit', 'uses' => 'DoctorController@PatientEditByHospitalForFront'));
    Route::post('rest/api/{hospitalId}/patient/{patientId}/update', array('as' => 'hospital.savepatient', 'uses' => 'DoctorController@updatePatientsByHospitalForFront'));

    Route::get('rest/api/{hospitalId}/profile', array('as' => 'hospital.viewprofile', 'uses' => 'DoctorController@getProfile'));

    Route::get('rest/api/{hospitalId}/editprofile', array('as' => 'hospital.editprofile', 'uses' => 'DoctorController@editProfile'));
    //Route::post('rest/api/pharmacy', array('as' => 'pharmacy.editpharmacy', 'uses' => 'PharmacyController@editPharmacy'));
    Route::post('rest/api/hospital', array('as' => 'hospital.edithospital', 'uses' => 'DoctorController@editHospital'));


    Route::get('rest/api/{hospitalId}/doctorlist', array('as' => 'hospital.doctors', 'uses' => 'DoctorController@getDoctorsForFront'));

    //Prescription
    Route::get('rest/api/{hospitalId}/patient/{patientId}/prescription-details', array('as' => 'hospital.patientdetails', 'uses' => 'DoctorController@PatientPrescriptionDetailsByHospitalForFront'));

    //Fee Receipts
    Route::get('rest/api/{hospitalId}/doctor/{doctorId}/feereceipts', array('as' => 'hospital.feereceipts', 'uses' => 'DoctorController@getFeeReceiptsForFront'));
    Route::get('rest/api/receipt/{receiptId}/details', array('as' => 'hospital.feereceiptdetails', 'uses' => 'DoctorController@getReceiptDetailsForFront'));
    Route::get('rest/api/{hospitalId}/addfeereceipt', array('as' => 'hospital.addfeereceipt', 'uses' => 'DoctorController@addFeeReceiptForFront'));
    Route::post('rest/api/savefeereceipt', array('as' => 'hospital.feereceipt', 'uses' => 'DoctorController@saveFeeReceiptForFront'));

    Route::get('receipt/{receiptId}/sms/{mobile}', array('as' => 'feereceipt.sendsms', 'uses' => 'DoctorController@forwardFeeReceiptBySMS'));

    Route::get('rest/api/receipt/{receiptId}/mail/{email}', array('as' => 'apifeereceipt.sendmail', 'uses' => 'DoctorController@forwardFeeReceiptApiByMail'));
    Route::get('receipt/{receiptId}/mail/{email}', array('as' => 'feereceipt.sendmail', 'uses' => 'DoctorController@forwardFeeReceiptByMail'));
    //Route::get('rest/api/patient/receipts/{receipts}/sms/{mobile}', array('as' => 'feereceipt.sendsms', 'uses' => 'DoctorController@forwardFeeReceiptsBySMS'));
    //Route

    Route::get('payment/online', array('as' => 'payment.online', 'uses' => 'DoctorController@onlinePayment'));
    Route::post('payment/process', array('as' => 'payment.process', 'uses' => 'DoctorController@processPayment'));
    Route::any('payment/response/success', array('as' => 'payment.success', 'uses' => 'DoctorController@successPayment'));
    Route::any('payment/response/failure', array('as' => 'payment.failure', 'uses' => 'DoctorController@failurePayment'));

    //PRINT ALL
    Route::get('rest/api/{hospitalId}/patient/{patientId}/print', array('as' => 'hospital.patientprintdetails', 'uses' => 'DoctorController@PatientPrintDetailsByHospitalForFront'));

    //Medical
    Route::get('rest/api/{hospitalId}/patient/{patientId}/medical-details', array('as' => 'hospital.patientmedicaldetails', 'uses' => 'DoctorController@PatientMedicalDetailsByHospitalForFront'));


    Route::get('rest/api/{patientId}/examinationdates', array('as' => 'doctor.examinationdates', 'uses' => 'DoctorController@getExaminationDates'));
    //Route::get('rest/api/{patientId}/examinations', array('as' => 'doctor.allexaminationdates', 'uses' => 'DoctorController@getExaminationDates'));

    Route::get('rest/api/{patientId}/generalexamination', array('as' => 'doctor.generalexamination', 'uses' => 'DoctorController@getPatientGeneralExamination'));
    Route::get('rest/api/{patientId}/familyillness', array('as' => 'doctor.familyillness', 'uses' => 'DoctorController@getPatientFamilyIllness'));
    Route::get('rest/api/{patientId}/pastillness', array('as' => 'doctor.patientpastillness', 'uses' => 'DoctorController@getPatientPastIllness'));
    Route::get('rest/api/{patientId}/patienthistory', array('as' => 'doctor.patienthistory', 'uses' => 'DoctorController@getPersonalHistory'));
    Route::get('rest/api/{patientId}/drughistory', array('as' => 'doctor.drughistory', 'uses' => 'DoctorController@getPatientDrugHistory'));
    Route::get('rest/api/{patientId}/pregnancydetails', array('as' => 'doctor.pregnancydetails', 'uses' => 'DoctorController@getPregnancyDetails'));
    Route::get('rest/api/{patientId}/symptomdetails', array('as' => 'doctor.symptomdetails', 'uses' => 'DoctorController@getPatientSymptoms'));
    Route::get('rest/api/{patientId}/complaintdetails', array('as' => 'doctor.complaintdetails', 'uses' => 'DoctorController@getPatientComplaints'));
    Route::get('rest/api/{patientId}/investigationdetails', array('as' => 'doctor.investigationdetails', 'uses' => 'DoctorController@getPatientInvestigations'));

    Route::get('rest/api/getsubsymptom', array('as' => 'doctor.getsubsymptomdetails', 'uses' => 'DoctorController@ajaxGetSubSymptoms'));
    Route::get('rest/api/getsymptomname', array('as' => 'doctor.getsymptomnamedetails', 'uses' => 'DoctorController@ajaxGetSymptomsName'));

    Route::get('rest/api/getcomplaint', array('as' => 'doctor.complaints', 'uses' => 'DoctorController@ajaxGetComplaints'));

    Route::get('rest/api/{hospitalId}/patient/{patientId}/add-medical-general', array('as' => 'hospital.patientmedicalgeneral', 'uses' => 'DoctorController@AddPatientMedicalGeneralByHospitalForFront'));
    Route::get('rest/api/{hospitalId}/patient/{patientId}/add-medical-family', array('as' => 'hospital.patientmedicalfamily', 'uses' => 'DoctorController@AddPatientMedicalFamilyByHospitalForFront'));
    Route::get('rest/api/{hospitalId}/patient/{patientId}/add-medical-past', array('as' => 'hospital.patientmedicalpast', 'uses' => 'DoctorController@AddPatientMedicalPastByHospitalForFront'));
    Route::get('rest/api/{hospitalId}/patient/{patientId}/add-medical-personal', array('as' => 'hospital.patientmedicalpersonal', 'uses' => 'DoctorController@AddPatientMedicalPersonalByHospitalForFront'));
    Route::get('rest/api/{hospitalId}/patient/{patientId}/add-medical-scan', array('as' => 'hospital.patientmedicalscan', 'uses' => 'DoctorController@AddPatientMedicalScanByHospitalForFront'));
    Route::get('rest/api/{hospitalId}/patient/{patientId}/add-medical-drug', array('as' => 'hospital.patientmedicaldrug', 'uses' => 'DoctorController@AddPatientMedicalDrugByHospitalForFront'));
    Route::get('rest/api/{hospitalId}/patient/{patientId}/add-medical-pregnancy', array('as' => 'hospital.patientmedicalpregnancy', 'uses' => 'DoctorController@AddPatientMedicalPregnancyByHospitalForFront'));
    Route::get('rest/api/{hospitalId}/patient/{patientId}/add-medical-symptom', array('as' => 'hospital.patientmedicalsymptom', 'uses' => 'DoctorController@AddPatientMedicalSymptomByHospitalForFront'));
    Route::get('rest/api/{hospitalId}/patient/{patientId}/add-medical-complaint', array('as' => 'hospital.patientmedicalcomplaint', 'uses' => 'DoctorController@AddPatientMedicalComplaintByHospitalForFront'));
    Route::get('rest/api/{hospitalId}/patient/{patientId}/add-medical-finding', array('as' => 'hospital.patientmedicalfinding', 'uses' => 'DoctorController@AddPatientMedicalFindingByHospitalForFront'));

    Route::post('rest/api/personalhistory', array('as' => 'doctor.savepersonalhistory', 'uses' => 'DoctorController@savePersonalHistory'));
    Route::post('rest/api/generalexamination', array('as' => 'doctor.savegeneralexamination', 'uses' => 'DoctorController@savePatientGeneralExamination'));
    Route::post('rest/api/pastillness', array('as' => 'doctor.savepastillness', 'uses' => 'DoctorController@savePatientPastIllness'));
    Route::post('rest/api/familyillness', array('as' => 'doctor.savefamilyillness', 'uses' => 'DoctorController@savePatientFamilyIllness'));
    Route::post('rest/api/pregnancydetails', array('as' => 'doctor.savepregnancydetails', 'uses' => 'DoctorController@savePatientPregnancyDetails'));

    Route::post('rest/api/drughistory', array('as' => 'doctor.savedrughistory', 'uses' => 'DoctorController@savePatientDrugHistory'));
    Route::post('rest/api/symptomdetails', array('as' => 'doctor.savesymptomdetails', 'uses' => 'DoctorController@savePatientSymptoms'));

    Route::post('rest/api/complaints', array('as' => 'doctor.savecomplaints', 'uses' => 'DoctorController@savePatientComplaints'));
    Route::post('rest/api/investigations', array('as' => 'doctor.saveinvestigations', 'uses' => 'DoctorController@savePatientInvestigationAndDiagnosis'));

    //Lab
    Route::get('rest/api/{hospitalId}/patient/{patientId}/lab-details', array('as' => 'hospital.patientlabdetails', 'uses' => 'DoctorController@PatientLabDetailsByHospitalForFront'));

    Route::get('rest/api/{hospitalId}/patient/{patientId}/add-lab-ultrasoundtests', array('as' => 'hospital.patientlabultrasoundtests', 'uses' => 'DoctorController@AddPatientLabUltraSoundTestsByHospitalForFront'));
    Route::get('rest/api/{hospitalId}/patient/{patientId}/add-lab-urinetests', array('as' => 'hospital.patientlaburinetests', 'uses' => 'DoctorController@AddPatientLabUrineTestsByHospitalForFront'));
    Route::get('rest/api/{hospitalId}/patient/{patientId}/add-lab-motiontests', array('as' => 'hospital.patientlabmotiontests', 'uses' => 'DoctorController@AddPatientLabMotionTestsByHospitalForFront'));
    Route::get('rest/api/{hospitalId}/patient/{patientId}/add-lab-bloodtests', array('as' => 'hospital.patientlabbloodtests', 'uses' => 'DoctorController@AddPatientLabBloodTestsByHospitalForFront'));
    Route::get('rest/api/{hospitalId}/patient/{patientId}/add-lab-scantests', array('as' => 'hospital.patientlabscantests', 'uses' => 'DoctorController@AddPatientLabScanTestsByHospitalForFront'));
    Route::get('rest/api/{hospitalId}/patient/{patientId}/add-lab-dentaltests', array('as' => 'hospital.patientlabdentaltests', 'uses' => 'DoctorController@addPatientDentalTestsForFront'));
    Route::get('rest/api/{hospitalId}/patient/{patientId}/add-lab-xraytests', array('as' => 'hospital.patientlabxraytests', 'uses' => 'DoctorController@addPatientXrayTestsForFront'));

    Route::post('rest/api/ultrasoundtests', array('as' => 'doctor.saveultrasoundtests', 'uses' => 'DoctorController@savePatientUltraSoundTests'));
    Route::post('rest/api/urinetests', array('as' => 'doctor.saveurinetests', 'uses' => 'DoctorController@savePatientUrineTests'));
    Route::post('rest/api/motiontests', array('as' => 'doctor.savemotiontests', 'uses' => 'DoctorController@savePatientMotionTests'));



    Route::post('rest/api/bloodtests', array('as' => 'doctor.savebloodtests', 'uses' => 'DoctorController@savePatientBloodTests'));
    Route::post('rest/api/scandetails', array('as' => 'doctor.savescandetails', 'uses' => 'DoctorController@savePatientScanDetails'));
    Route::post('rest/api/dentaltests', array('as' => 'doctor.dentaltests', 'uses' => 'DoctorController@savePatientDentalTests'));
    Route::post('rest/api/xraytests', array('as' => 'doctor.xraytests', 'uses' => 'DoctorController@savePatientXrayTests'));

    Route::get('rest/api/{patientId}/ultrasoundtests', array('as' => 'doctor.ultrasoundtests', 'uses' => 'DoctorController@getPatientUltraSoundTests'));
    Route::get('rest/api/{patientId}/urinetests', array('as' => 'doctor.urinetests', 'uses' => 'DoctorController@getPatientUrineTests'));
    Route::get('rest/api/{patientId}/motiontests', array('as' => 'doctor.motiontests', 'uses' => 'DoctorController@getPatientMotionTests'));
    Route::get('rest/api/{patientId}/bloodtests', array('as' => 'doctor.bloodtests', 'uses' => 'DoctorController@getPatientBloodTests'));
    Route::get('rest/api/{patientId}/scandetails', array('as' => 'doctor.scandetails', 'uses' => 'DoctorController@getPatientScanDetails'));
    Route::get('rest/api/{patientId}/dentaltests', array('as' => 'doctor.dentaltests', 'uses' => 'DoctorController@getPatientDentalTests'));
    Route::get('rest/api/{patientId}/xraytests', array('as' => 'doctor.xraytests', 'uses' => 'DoctorController@getPatientXrayTests'));

    //RECEIPT


    Route::get('rest/api/{hospitalId}/patient/{patientId}/labtestreceipts', array('as' => 'patient.labtestreceipts', 'uses' => 'DoctorController@getLabTestDetailsForReceipt'));
    Route::get('rest/api/{hospitalId}/patient/{patientId}/receiptdetails', array('as' => 'patient.receiptdetails', 'uses' => 'DoctorController@getPatientReceiptDetails'));

    Route::post('rest/api/savelabreceipts', array('as' => 'patient.savelabreceipts', 'uses' => 'DoctorController@saveLabReceiptDetailsForPatient'));
    Route::get('rest/api/{hospitalId}/patient/{patientId}/labreceipts', array('as' => 'patient.labreceipts', 'uses' => 'DoctorController@getLabReceiptsByPatient'));
    //Route::get('rest/api/savelabreceipts', array('as' => 'patient.savelabreceipts', 'uses' => 'DoctorController@saveLabReceiptDetailsForPatient'));
    //Route::get('rest/api/{hospitalId}/patient/{patientId}/receiptdetails', array('as' => 'patient.receiptdetails', 'uses' => 'DoctorController@getPatientReceiptDetails'));
});

Route::group(array('prefix' => 'hospital', 'namespace' => 'Doctor'), function() {
    Route::get('{id}/dashboard', function () {
        //return redirect('fronthospital/rest/api/'.$id.'/profile');
        return view('portal.hospital-dashboard');
    });
    Route::post('send', 'DoctorController@sendEmail');

    Route::get('rest/api/{hospitalId}/dashboard', array('as' => 'doctor.dashboard', 'uses' => 'DoctorApiController@getDashboardDetails'));
    Route::get('rest/api/{hospitalId}/patients/appointments', array('as' => 'patient.appointmentcategory', 'uses' => 'DoctorApiController@getPatientsByAppointmentCategory'));

    Route::get('rest/{hospitalId}/patients/appointments', array('as' => 'patient.appointmentcategory', 'uses' => 'DoctorController@getPatientsByAppointmentCategoryForFront'));

    Route::get('rest/api/{hospitalId}/patients/{patientId}/appointmentdates', array('as' => 'patient.appointmentdates', 'uses' => 'DoctorApiController@getPatientAppointmentDates'));

    Route::get('rest/api/{hospitalId}/patients/{patientId}/labtests', array('as' => 'patient.alllabtests', 'uses' => 'DoctorController@getPatientLabTests'));
    Route::get('rest/api/labtests/{labtestId}/labtestdetails', array('as' => 'patient.labtestdetails', 'uses' => 'DoctorController@getLabTestDetailsByPatient'));

    Route::get('rest/api/{patientId}/labtestreceipts', array('as' => 'patient.labtestreceipts', 'uses' => 'DoctorController@getLabTestDetailsForReceipt'));

    //Route::post('rest/labtestreceipts', array('as' => 'patient.savelabreceipts', 'uses' => 'DoctorController@saveLabReceiptDetailsForPatient'));
    //Route::get('rest/labtestreceipts', array('as' => 'patient.savelabreceipts', 'uses' => 'DoctorController@saveLabReceiptDetailsForPatient'));

    Route::get('rest/api/specialties', array('as' => 'hospital.specialties', 'uses' => 'DoctorApiController@getAllSpecialties'));
    Route::get('rest/api/{specialtyId}/referraldoctors', array('as' => 'specialties.referraldoctors', 'uses' => 'DoctorApiController@getDoctorsBySpecialty'));
    Route::post('rest/api/referraldoctor', array('as' => 'specialties.savereferraldoctor', 'uses' => 'DoctorApiController@saveReferralDoctor'));
    Route::get('rest/api/referraldoctor/{referralId}/details', array('as' => 'hospital.referraldoctordetails', 'uses' => 'DoctorApiController@getReferralDoctorDetails'));
    /* Route::group(['middleware' => 'prescription.auth'], function () {

      Route::get('rest/api/patient', array('as' => 'patient.patient', 'uses' => 'DoctorController@searchPatientByPid'));
      Route::get('rest/api/{hospitalId}/doctors', array('as' => 'hospital.doctors', 'uses' => 'DoctorController@getDoctorsByHospitalId'));

      }); */

    //Route::get('rest/api/{doctorId}/doctordetails', array('as' => 'doctor.details', 'uses' => 'DoctorController@getDoctorDetails'));
    //MOBILE
    Route::get('rest/api/{hospitalId}/doctornames', array('as' => 'hospital.doctors', 'uses' => 'DoctorController@getDoctorNames'));
    Route::get('rest/api/{hospitalId}/patientnames', array('as' => 'patient.searchnames', 'uses' => 'DoctorController@getPatientNames'));

    Route::get('rest/api/hospitals', array('as' => 'doctor.hospitals', 'uses' => 'DoctorController@getHospitalByKeyword'));
    Route::post('rest/api/login', array('as' => 'doctor.login', 'uses' => 'DoctorController@login'));
    //Route::get('rest/api/login', array('as' => 'doctor.login', 'uses' => 'DoctorController@login'));
    Route::get('rest/api/{hospitalId}/{doctorId}/appointments', array('as' => 'doctor.appointments', 'uses' => 'DoctorController@getAppointmentsByHospitalAndDoctor'));
    Route::get('rest/api/{hospitalId}/patients', array('as' => 'hospital.patients', 'uses' => 'DoctorController@getPatientsByHospital'));
    Route::get('rest/api/{patientId}/details', array('as' => 'patient.details', 'uses' => 'DoctorController@getPatientDetailsById'));
    Route::get('rest/api/{patientId}/profile', array('as' => 'patient.profile', 'uses' => 'DoctorController@getPatientProfile'));
    Route::get('rest/api/{hospitalId}/doctor/{doctorId}/prescriptions', array('as' => 'hospital.prescriptions', 'uses' => 'DoctorController@getPrescriptions'));
    Route::get('rest/api/{patientId}/prescriptions', array('as' => 'patient.prescriptions', 'uses' => 'DoctorController@getPrescriptionByPatient'));
    Route::get('rest/api/prescriptions', array('as' => 'patient.allprescriptions', 'uses' => 'DoctorController@getAllPrescriptions'));
    Route::get('rest/api/prescription/{prescriptionId}', array('as' => 'patient.prescriptiondetails', 'uses' => 'DoctorController@getPrescriptionDetails'));
    Route::get('rest/api/patients', array('as' => 'patient.names', 'uses' => 'DoctorController@searchPatientByName'));
    Route::get('rest/api/patient', array('as' => 'patient.patient', 'uses' => 'DoctorController@searchPatientByPid'));
    Route::get('rest/api/labtests', array('as' => 'lab.labtests', 'uses' => 'DoctorController@getLabTests'));
    Route::get('rest/api/patient/{patientId}/labtests', array('as' => 'patient.labtests', 'uses' => 'DoctorController@getLabTestsByPatient'));
    Route::get('rest/api/{hospitalId}/doctor/{doctorId}/labtests', array('as' => 'hospital.labtests', 'uses' => 'DoctorController@getLabTestsForPatient'));
    Route::get('rest/api/labtests/{labTestId}', array('as' => 'patient.labtestdetails', 'uses' => 'DoctorController@getLabTestDetails'));
    Route::get('rest/api/brands', array('as' => 'drug.brands', 'uses' => 'DoctorController@getTradeNames'));
    Route::get('rest/api/formulations', array('as' => 'formulation.names', 'uses' => 'DoctorController@getFormulationNames'));
    //Route::post('rest/api/brands', array('as' => 'drug.brands', 'uses' => 'DoctorController@getBrandNames'));
    Route::post('rest/api/patient/prescription', array('as' => 'patient.saveprescription', 'uses' => 'DoctorController@savePatientPrescription'));
    Route::post('rest/api/patient/labtests', array('as' => 'patient.savelabtests', 'uses' => 'DoctorController@savePatientLabTests'));

    Route::post('rest/api/register', array('as' => 'patient.register', 'uses' => 'DoctorController@savePatientProfile'));
    Route::put('rest/api/profile', array('as' => 'patient.profile', 'uses' => 'DoctorController@editPatientProfile'));

    Route::get('rest/api/pidorname', array('as' => 'patient.searchpatient', 'uses' => 'DoctorController@searchByPatientByPidOrName'));
    Route::get('rest/api/{hospitalId}/patientnames', array('as' => 'patient.searchpatientbyhospital', 'uses' => 'DoctorController@searchPatientByHospitalAndName'));

    Route::post('rest/api/appointment', array('as' => 'patient.appointment', 'uses' => 'DoctorController@saveNewAppointment'));
    Route::put('rest/api/cancelappointment', array('as' => 'patient.cancelappointment', 'uses' => 'DoctorController@cancelAppointment'));
    Route::put('rest/api/transferappointment', array('as' => 'patient.transferappointment', 'uses' => 'DoctorController@transferAppointment'));

    Route::get('rest/api/{hospitalId}/doctors', array('as' => 'hospital.doctors', 'uses' => 'DoctorController@getDoctorsByHospitalId'));

    Route::get('rest/api/patientstatus', array('as' => 'hospital.isnewpatient', 'uses' => 'DoctorController@checkIsNewPatient'));

    Route::get('rest/api/doctor/{doctorId}/doctordetails', array('as' => 'hospital.doctordetails', 'uses' => 'DoctorController@getDoctorDetails'));

    //Fee Receipts
    Route::get('rest/api/{hospitalId}/doctor/{doctorId}/feereceipts', array('as' => 'hospital.feereceipts', 'uses' => 'DoctorController@getFeeReceipts'));
    Route::get('rest/api/receipt/{receiptId}/details', array('as' => 'hospital.feereceiptdetails', 'uses' => 'DoctorController@getReceiptDetails'));
    Route::post('rest/api/feereceipt', array('as' => 'hospital.feereceipt', 'uses' => 'DoctorController@saveFeeReceipt'));

    Route::get('rest/api/receipt/{receiptId}/sms/{mobile}', array('as' => 'apifeereceipt.sendsms', 'uses' => 'DoctorController@forwardFeeReceiptApiBySMS'));
    Route::get('rest/api/doctor/hospitals', array('as' => 'doctor.hospitals', 'uses' => 'DoctorController@getHospitalsForDoctor'));
    Route::get('rest/api/{doctorId}/hospitals', array('as' => 'doctor.associatedhospitals', 'uses' => 'DoctorController@getHospitalsByDoctorId'));
});

Route::group(array('prefix' => 'hospital', 'namespace' => 'Pharmacy'), function() {

    Route::get('rest/api/patient/prescription/{prescriptionId}/mail/{email}', array('as' => 'patient.sendemail', 'uses' => 'PharmacyController@forwardPrescriptionDetailsByMail'));
    //Route::post('rest/api/patient/prescription/{prescriptionId}/mail', array('as' => 'patient.sendemail', 'uses' => 'PharmacyController@forwardPrescriptionDetailsByMail'));
    Route::get('rest/api/patient/prescription/{prescriptionId}/sms/{mobile}', array('as' => 'patient.sendsms', 'uses' => 'PharmacyController@forwardPrescriptionDetailsBySMS'));
});

Route::group(array('prefix' => 'hospital', 'namespace' => 'Lab'), function() {

    Route::get('rest/api/patient/labtest/{labTestId}/mail/{email}', array('as' => 'patient.sendemail', 'uses' => 'LabController@forwardLabDetailsByMail'));
    Route::get('rest/api/patient/labtest/{labTestId}/sms/{mobile}', array('as' => 'patient.sendsms', 'uses' => 'LabController@forwardLabDetailsBySMS'));
});

Route::group(['prefix' => 'pharmacy'], function() {
    Route::get('{id}/dashboard', function ($id) {
        return redirect('pharmacy/rest/api/' . $id . '/profile');
        //return view('portal.pharmacy-dashboard');
    });

    /*
      Route::get('{id}/dashboard', function () {
      return view('portal.pharmacy-dashboard');
      });
     */


    Route::group(['namespace' => 'Pharmacy'], function() {
        Route::get('rest/api/{pharmacyId}/profile', array('as' => 'pharmacy.viewprofile', 'uses' => 'PharmacyController@getProfile'));
        Route::get('rest/api/{pharmacyId}/hospital/{hospitalId}/patients', array('as' => 'pharmacy.patients', 'uses' => 'PharmacyController@getPatientListForPharmacy'));
        Route::get('rest/api/{pharmacyId}/hospital/{hospitalId}/prescriptions', array('as' => 'pharmacy.prescriptions', 'uses' => 'PharmacyController@getPrescriptionListForPharmacy'));
        Route::get('rest/api/prescription/{prescriptionId}', array('as' => 'pharmacy.prescriptiondetails', 'uses' => 'PharmacyController@getPrescriptionDetails'));
        Route::get('rest/api/prescription', array('as' => 'pharmacy.searchbyprid', 'uses' => 'PharmacyController@getPrescriptionByPrid'));

        Route::get('rest/api/{pharmacyId}/editprofile', array('as' => 'pharmacy.editprofile', 'uses' => 'PharmacyController@editProfile'));
        //Route::post('rest/api/pharmacy', array('as' => 'pharmacy.editpharmacy', 'uses' => 'PharmacyController@editPharmacy'));
        Route::post('rest/api/pharmacy', array('as' => 'pharmacy.editpharmacy', 'uses' => 'PharmacyController@editPharmacy'));

        Route::get('rest/api/prescription/{prescriptionId}/mail', array('as' => 'patient.sendemail', 'uses' => 'PharmacyController@forwardPrescriptionDetailsByMail'));
        Route::get('rest/api/prescription/{prescriptionId}/sms', array('as' => 'patient.sendsms', 'uses' => 'PharmacyController@forwardPrescriptionDetailsBySMS'));

        //Route::get('rest/api/{labId}/changepassword', array('as' => 'lab.changepassword', 'uses' => 'PharmacyController@editChangePassword'));
        //Route::post('rest/api/pharmacy', array('as' => 'pharmacy.editpharmacy', 'uses' => 'PharmacyController@editPharmacy'));
        //Route::get('rest/api/lab', array('as' => 'lab.editlab', 'uses' => 'PharmacyController@saveChangesPassword'));
    });

    /* Route::group(['namespace' => 'Common'], function()
      {
      //Route::get('rest/api/{pharmacyId}/profile', array('as' => 'pharmacy.viewprofile', 'uses' => 'PharmacyController@getProfile'));
      Route::get('rest/api/prescription/{prescriptionId}', array('as' => 'pharmacy.patients', 'uses' => 'CommonController@getPatientListForPharmacy'));
      }); */
});


Route::group(['prefix' => 'doctor'], function() {


    Route::post('/changehospital', array('as' => 'doctor.changehospital', 'uses' => 'Doctor\DoctorController@changeHospital'));


    Route::get('{id}/dashboard', function () {
        return view('portal.doctor-dashboard');
    });


    Route::get('hospital/{id}/doctor/{doctorId}/dashboard', array('as' => 'doctor.dashboard', 'uses' => 'Doctor\DoctorController@getDashboardDetailsForDoctor'));
    //Route::get('{doctorId}/hospital/{hospitalId}/patients/appointments', array('as' => 'patient.appointmentcategory', 'uses' => 'DoctorController@getPatientsByAppointmentCategoryForDoctor'));

    Route::group(['namespace' => 'Doctor'], function() {

        Route::get('{doctorId}/hospital/{hospitalId}/dashboard', array('as' => 'doctor.dashboard', 'uses' => 'DoctorApiController@getDashboardDetailsForDoctor'));
        Route::get('{doctorId}/hospital/{hospitalId}/futureappointments', array('as' => 'doctor.futureappointments', 'uses' => 'DoctorApiController@getFutureAppointmentsForDashboard'));


        Route::get('rest/api/mainsymptoms', array('as' => 'doctor.symptoms', 'uses' => 'DoctorApiController@getMainSymptoms'));
        Route::get('rest/api/{mainsymptomId}/subsymptoms', array('as' => 'doctor.subsymptoms', 'uses' => 'DoctorApiController@getSubSymptomsForMainSymptoms'));
        Route::get('rest/api/{subsymptomId}/symptoms', array('as' => 'doctor.symptoms', 'uses' => 'DoctorApiController@getSymptomsForSubSymptoms'));
        Route::get('rest/api/{patientId}/patienthistory', array('as' => 'doctor.patienthistory', 'uses' => 'DoctorApiController@getPersonalHistory'));
        Route::get('rest/api/{patientId}/pastillness', array('as' => 'doctor.patientpastillness', 'uses' => 'DoctorApiController@getPatientPastIllness'));
        Route::get('rest/api/{patientId}/familyillness', array('as' => 'doctor.familyillness', 'uses' => 'DoctorApiController@getPatientFamilyIllness'));
        Route::get('rest/api/{patientId}/generalexamination', array('as' => 'doctor.generalexamination', 'uses' => 'DoctorApiController@getPatientGeneralExamination'));
        Route::post('rest/api/personalhistory', array('as' => 'doctor.savepersonalhistory', 'uses' => 'DoctorApiController@savePersonalHistory'));
        Route::post('rest/api/generalexamination', array('as' => 'doctor.savegeneralexamination', 'uses' => 'DoctorApiController@savePatientGeneralExamination'));
        Route::post('rest/api/pastillness', array('as' => 'doctor.savepastillness', 'uses' => 'DoctorApiController@savePatientPastIllness'));
        Route::post('rest/api/familyillness', array('as' => 'doctor.savefamilyillness', 'uses' => 'DoctorApiController@savePatientFamilyIllness'));
        Route::get('rest/api/{patientId}/examinationdates', array('as' => 'doctor.examinationdates', 'uses' => 'DoctorApiController@getExaminationDates'));

        Route::get('rest/api/{patientId}/latestappointmentdate', array('as' => 'doctor.latestappointmentdate', 'uses' => 'DoctorController@getLatestAppointmentDateForPatient'));

        Route::get('rest/api/complainttypes', array('as' => 'doctor.complainttypes', 'uses' => 'DoctorApiController@getComplaintTypes'));
        Route::get('rest/api/{complaintTypeId}/complaints', array('as' => 'doctor.complaints', 'uses' => 'DoctorApiController@getComplaints'));
        Route::post('rest/api/complaints', array('as' => 'doctor.savecomplaints', 'uses' => 'DoctorApiController@savePatientComplaints'));
        Route::get('rest/api/{patientId}/complaintdetails', array('as' => 'doctor.complaintdetails', 'uses' => 'DoctorApiController@getPatientComplaints'));

        Route::post('rest/api/investigations', array('as' => 'doctor.saveinvestigations', 'uses' => 'DoctorApiController@savePatientInvestigationAndDiagnosis'));
        Route::get('rest/api/{patientId}/investigationdetails', array('as' => 'doctor.investigationdetails', 'uses' => 'DoctorApiController@getPatientInvestigations'));

        //Route::get('rest/api/{patientId}/examinations', array('as' => 'doctor.allexaminations', 'uses' => 'DoctorApiController@getPatientExaminations'));

        Route::get('rest/api/{patientId}/pregnancydetails', array('as' => 'doctor.pregnancydetails', 'uses' => 'DoctorApiController@getPregnancyDetails'));
        Route::post('rest/api/pregnancydetails', array('as' => 'doctor.savepregnancydetails', 'uses' => 'DoctorApiController@savePatientPregnancyDetails'));
        Route::get('rest/api/{patientId}/scandetails', array('as' => 'doctor.scandetails', 'uses' => 'DoctorApiController@getPatientScanDetails'));
        Route::post('rest/api/scandetails', array('as' => 'doctor.savescandetails', 'uses' => 'DoctorApiController@savePatientScanDetails'));

        Route::get('rest/api/{patientId}/symptomdetails', array('as' => 'doctor.symptomdetails', 'uses' => 'DoctorApiController@getPatientSymptoms'));
        Route::post('rest/api/symptomdetails', array('as' => 'doctor.savesymptomdetails', 'uses' => 'DoctorApiController@savePatientSymptoms'));

        Route::get('rest/api/{patientId}/drughistory', array('as' => 'doctor.drughistory', 'uses' => 'DoctorApiController@getPatientDrugHistory'));
        Route::post('rest/api/drughistory', array('as' => 'doctor.savedrughistory', 'uses' => 'DoctorApiController@savePatientDrugHistory'));

        Route::get('rest/api/{patientId}/urinetests', array('as' => 'doctor.urinetests', 'uses' => 'DoctorApiController@getPatientUrineTests'));
        Route::post('rest/api/urinetests', array('as' => 'doctor.saveurinetests', 'uses' => 'DoctorApiController@savePatientUrineTests'));
        Route::get('rest/api/{patientId}/motiontests', array('as' => 'doctor.motiontests', 'uses' => 'DoctorApiController@getPatientMotionTests'));
        Route::post('rest/api/motiontests', array('as' => 'doctor.savemotiontests', 'uses' => 'DoctorApiController@savePatientMotionTests'));
        Route::get('rest/api/{patientId}/bloodtests', array('as' => 'doctor.bloodtests', 'uses' => 'DoctorApiController@getPatientBloodTests'));
        //Route::post('rest/api/bloodtests', array('as' => 'doctor.savebloodtests', 'uses' => 'DoctorApiController@savePatientBloodTests'));
        //Route::group(array('middleware' => ['prescription.tests']), function()
        //{
        Route::post('rest/api/bloodtests', array('as' => 'doctor.savebloodtests', 'uses' => 'DoctorApiController@savePatientBloodTests'));

        //});

        Route::get('rest/api/{patientId}/ultrasoundtests', array('as' => 'doctor.ultrasoundtests', 'uses' => 'DoctorApiController@getPatientUltraSoundTests'));
        Route::post('rest/api/ultrasoundtests', array('as' => 'doctor.saveultrasoundtests', 'uses' => 'DoctorApiController@savePatientUltraSoundTests'));

        Route::get('rest/api/{patientId}/dentaltests', array('as' => 'doctor.dentaltests', 'uses' => 'DoctorApiController@getPatientDentalTests'));
        Route::post('rest/api/dentaltests', array('as' => 'doctor.dentaltests', 'uses' => 'DoctorApiController@savePatientDentalTests'));

        Route::get('rest/api/{patientId}/xraytests', array('as' => 'doctor.xraytests', 'uses' => 'DoctorApiController@getPatientXrayTests'));
        Route::post('rest/api/xraytests', array('as' => 'doctor.xraytests', 'uses' => 'DoctorApiController@savePatientXRayTests'));

        Route::get('rest/api/familyillness', array('as' => 'doctor.allfamilyillness', 'uses' => 'DoctorApiController@getAllFamilyIllness'));
        Route::get('rest/api/pastillness', array('as' => 'doctor.allpastillness', 'uses' => 'DoctorApiController@getAllPastIllness'));
        Route::get('rest/api/generalexaminations', array('as' => 'doctor.allgeneralexaminations', 'uses' => 'DoctorApiController@getAllGeneralExaminations'));
        Route::get('rest/api/personalhistory', array('as' => 'doctor.allpersonalhistory', 'uses' => 'DoctorApiController@getAllPersonalHistory'));
        Route::get('rest/api/pregnancy', array('as' => 'doctor.allpregnancydetails', 'uses' => 'DoctorApiController@getAllPregnancy'));
        Route::get('rest/api/scans', array('as' => 'doctor.allscandetails', 'uses' => 'DoctorApiController@getAllScans'));
        Route::get('rest/api/dentaltests', array('as' => 'doctor.alldentaltests', 'uses' => 'DoctorApiController@getAllDentalItems'));
        Route::get('rest/api/xraytests', array('as' => 'doctor.allxraytests', 'uses' => 'DoctorApiController@getAllXRayItems'));


        //WEBSITE


        Route::get('rest/api/{doctorId}/hospital/{hospitalId}/patients', array('as' => 'doctor.patients', 'uses' => 'DoctorController@getPatientsByDoctorForFront'));
        Route::get('rest/api/{doctorId}/hospital/{hospitalId}/patient/{patientId}/details', array('as' => 'doctor.patientdetails', 'uses' => 'DoctorController@PatientDetailsByDoctorForFront'));


        Route::get('rest/api/{doctorId}/hospital/{hospitalId}/patient/{patientId}/edit', array('as' => 'doctor.patientdetailsedit', 'uses' => 'DoctorController@PatientEditByDoctorForFront'));
        Route::post('rest/api/{doctorId}/hospital/{hospitalId}/patient/{patientId}/update', array('as' => 'doctor.savepatient', 'uses' => 'DoctorController@updatePatientsByDoctorForFront'));


        //NEW By VIMAL

        Route::get('{doctorId}/hospital/{hospitalId}/patients', array('as' => 'doctor.patients', 'uses' => 'DoctorController@getPatientsByDoctorForFront'));
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/details', array('as' => 'doctor.patientdetails', 'uses' => 'DoctorController@PatientDetailsByDoctorForFront'));


        //Appointment
        Route::get('{doctorId}/hospital/{hospitalId}/addpatientwithappointment', array('as' => 'hospital.addpatientwithappointment', 'uses' => 'DoctorController@addPatientWithAppointmentByHospitalForDoctor'));
        Route::post('{doctorId}/hospital/{hospitalId}/savepatientwithappointment', array('as' => 'hospital.savepatientwithappointment', 'uses' => 'DoctorController@savePatientWithAppointmentByHospitalForDoctor'));

        Route::get('{doctorId}/hospital/{hospitalId}/patients/appointments', array('as' => 'patient.appointmentcategory', 'uses' => 'DoctorController@getPatientsByAppointmentCategoryForDoctor'));
        Route::get('{doctorId}/hospital/{hospitalId}/appointments', array('as' => 'patient.appointmentdate', 'uses' => 'DoctorApiController@getPatientsByAppointmentDate'));

        Route::get('patients/appointments/{appointmentId}/details', array('as' => 'patient.appointmentdetails', 'uses' => 'DoctorController@getAppointmentDetailsForDoctor'));

        Route::get('{doctorId}/hospital/{hospitalId}/patientnames', array('as' => 'hospital.searchnames', 'uses' => 'DoctorController@getPatientNamesForHospital'));
        Route::get('rest/api/{patientId}/details', array('as' => 'hospital.details', 'uses' => 'DoctorController@getPatientDetailsById'));
        Route::get('rest/api/{specialtyId}/referraldoctors', array('as' => 'hospital.referraldoctors', 'uses' => 'DoctorController@getDoctorsBySpecialty'));


        Route::get('rest/api/{doctorId}/hospital/{hospitalId}/futureappointments', array('as' => 'doctor.futureappointments', 'uses' => 'DoctorController@getFutureAppointmentsForDoctorDashboard'));

        //Medical
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/medical-details', array('as' => 'hospital.patientmedicaldetails', 'uses' => 'DoctorController@PatientMedicalDetailsByHospitalForDoctor'));

        Route::get('{doctorId}/patient/{patientId}/examinationdates', array('as' => 'doctor.examinationdates', 'uses' => 'DoctorController@getExaminationDatesForDoctor'));

        Route::get('{doctorId}/patient/{patientId}/generalexamination', array('as' => 'doctor.generalexamination', 'uses' => 'DoctorController@getPatientGeneralExaminationForDoctor'));
        Route::get('{doctorId}/patient/{patientId}/familyillness', array('as' => 'doctor.familyillness', 'uses' => 'DoctorController@getPatientFamilyIllnessForDoctor'));
        Route::get('{doctorId}/patient/{patientId}/pastillness', array('as' => 'doctor.patientpastillness', 'uses' => 'DoctorController@getPatientPastIllnessForDoctor'));
        Route::get('{doctorId}/patient/{patientId}/patienthistory', array('as' => 'doctor.patienthistory', 'uses' => 'DoctorController@getPersonalHistoryForDoctor'));
        Route::get('{doctorId}/patient/{patientId}/drughistory', array('as' => 'doctor.drughistory', 'uses' => 'DoctorController@getPatientDrugHistoryForDoctor'));
        Route::get('{doctorId}/patient/{patientId}/pregnancydetails', array('as' => 'doctor.pregnancydetails', 'uses' => 'DoctorController@getPregnancyDetailsForDoctor'));
        Route::get('{doctorId}/patient/{patientId}/symptomdetails', array('as' => 'doctor.symptomdetails', 'uses' => 'DoctorController@getPatientSymptomsForDoctor'));

        Route::get('rest/api/getsubsymptom', array('as' => 'doctor.getsubsymptomdetails', 'uses' => 'DoctorController@ajaxGetSubSymptoms'));
        Route::get('rest/api/getsymptomname', array('as' => 'doctor.getsymptomnamedetails', 'uses' => 'DoctorController@ajaxGetSymptomsName'));

        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/add-medical-general', array('as' => 'hospital.patientmedicalgeneral', 'uses' => 'DoctorController@AddPatientMedicalGeneralByHospitalForDoctor'));
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/add-medical-family', array('as' => 'hospital.patientmedicalfamily', 'uses' => 'DoctorController@AddPatientMedicalFamilyByHospitalForDoctor'));
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/add-medical-past', array('as' => 'hospital.patientmedicalpast', 'uses' => 'DoctorController@AddPatientMedicalPastByHospitalForDoctor'));
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/add-medical-personal', array('as' => 'hospital.patientmedicalpersonal', 'uses' => 'DoctorController@AddPatientMedicalPersonalByHospitalForDoctor'));
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/add-medical-scan', array('as' => 'hospital.patientmedicalscan', 'uses' => 'DoctorController@AddPatientMedicalScanByHospitalForDoctor'));
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/add-medical-drug', array('as' => 'hospital.patientmedicaldrug', 'uses' => 'DoctorController@AddPatientMedicalDrugByHospitalForDoctor'));
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/add-medical-pregnancy', array('as' => 'hospital.patientmedicalpregnancy', 'uses' => 'DoctorController@AddPatientMedicalPregnancyByHospitalForDoctor'));
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/add-medical-symptom', array('as' => 'hospital.patientmedicalsymptom', 'uses' => 'DoctorController@AddPatientMedicalSymptomByHospitalForDoctor'));

        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/add-medical-complaint', array('as' => 'hospital.patientmedicalcomplaint', 'uses' => 'DoctorController@AddPatientMedicalComplaintByHospitalForDoctor'));
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/add-medical-finding', array('as' => 'hospital.patientmedicalfinding', 'uses' => 'DoctorController@AddPatientMedicalFindingByHospitalForDoctor'));

        Route::post('personalhistory', array('as' => 'doctor.savepersonalhistory', 'uses' => 'DoctorController@savePersonalHistoryForDoctor'));
        Route::post('generalexamination', array('as' => 'doctor.savegeneralexamination', 'uses' => 'DoctorController@savePatientGeneralExaminationForDoctor'));
        Route::post('pastillness', array('as' => 'doctor.savepastillness', 'uses' => 'DoctorController@savePatientPastIllnessForDoctor'));
        Route::post('familyillness', array('as' => 'doctor.savefamilyillness', 'uses' => 'DoctorController@savePatientFamilyIllnessForDoctor'));
        Route::post('pregnancydetails', array('as' => 'doctor.savepregnancydetails', 'uses' => 'DoctorController@savePatientPregnancyDetailsForDoctor'));
        Route::post('drughistory', array('as' => 'doctor.savedrughistory', 'uses' => 'DoctorController@savePatientDrugHistoryForDoctor'));
        Route::post('symptomdetails', array('as' => 'doctor.savesymptomdetails', 'uses' => 'DoctorController@savePatientSymptomsForDoctor'));

        Route::post('complaints', array('as' => 'doctor.savecomplaints', 'uses' => 'DoctorController@savePatientComplaintsForDoctor'));
        Route::post('investigations', array('as' => 'doctor.saveinvestigations', 'uses' => 'DoctorController@savePatientInvestigationAndDiagnosisForDoctor'));

//Prescription
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/prescription-details', array('as' => 'hospital.patientdetails', 'uses' => 'DoctorController@PatientPrescriptionDetailsByHospitalForDoctor'));



//Lab
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/lab-details', array('as' => 'hospital.patientlabdetails', 'uses' => 'DoctorController@PatientLabDetailsByHospitalForDoctor'));

        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/add-lab-ultrasoundtests', array('as' => 'hospital.patientlabultrasoundtests', 'uses' => 'DoctorController@AddPatientLabUltraSoundTestsByHospitalForDoctor'));
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/add-lab-urinetests', array('as' => 'hospital.patientlaburinetests', 'uses' => 'DoctorController@AddPatientLabUrineTestsByHospitalForDoctor'));
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/add-lab-motiontests', array('as' => 'hospital.patientlabmotiontests', 'uses' => 'DoctorController@AddPatientLabMotionTestsByHospitalForDoctor'));
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/add-lab-bloodtests', array('as' => 'hospital.patientlabbloodtests', 'uses' => 'DoctorController@AddPatientLabBloodTestsByHospitalForDoctor'));
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/add-lab-scantests', array('as' => 'hospital.patientlabscantests', 'uses' => 'DoctorController@AddPatientLabScanTestsByHospitalForDoctor'));
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/add-lab-dentaltests', array('as' => 'hospital.patientlabdentaltests', 'uses' => 'DoctorController@addPatientDentalTestsForDoctor'));
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/add-lab-xraytests', array('as' => 'hospital.patientlabxraytests', 'uses' => 'DoctorController@addPatientXrayTestsForDoctor'));

        Route::post('ultrasoundtests', array('as' => 'doctor.saveultrasoundtests', 'uses' => 'DoctorController@savePatientUltraSoundTestsForDoctor'));
        Route::post('urinetests', array('as' => 'doctor.saveurinetests', 'uses' => 'DoctorController@savePatientUrineTestsForDoctor'));
        Route::post('motiontests', array('as' => 'doctor.savemotiontests', 'uses' => 'DoctorController@savePatientMotionTestsForDoctor'));
        Route::post('bloodtests', array('as' => 'doctor.savebloodtests', 'uses' => 'DoctorController@savePatientBloodTestsForDoctor'));
        Route::post('scandetails', array('as' => 'doctor.savescandetails', 'uses' => 'DoctorController@savePatientScanDetailsForDoctor'));
        Route::post('dentaltests', array('as' => 'doctor.dentaltests', 'uses' => 'DoctorController@savePatientDentalTestsForDoctor'));
        Route::post('xraytests', array('as' => 'doctor.xraytests', 'uses' => 'DoctorController@savePatientXrayTestsForDoctor'));

        Route::get('{doctorId}/patient/{patientId}/ultrasoundtests', array('as' => 'doctor.ultrasoundtests', 'uses' => 'DoctorController@getPatientUltraSoundTestsForDoctor'));
        Route::get('{doctorId}/patient/{patientId}/urinetests', array('as' => 'doctor.urinetests', 'uses' => 'DoctorController@getPatientUrineTestsForDoctor'));
        Route::get('{doctorId}/patient/{patientId}/motiontests', array('as' => 'doctor.motiontests', 'uses' => 'DoctorController@getPatientMotionTestsForDoctor'));
        Route::get('{doctorId}/patient/{patientId}/bloodtests', array('as' => 'doctor.bloodtests', 'uses' => 'DoctorController@getPatientBloodTestsForDoctor'));
        Route::get('{doctorId}/patient/{patientId}/scandetails', array('as' => 'doctor.scandetails', 'uses' => 'DoctorController@getPatientScanDetailsForDoctor'));
        Route::get('{doctorId}/patient/{patientId}/dentaltests', array('as' => 'doctor.dentaltests', 'uses' => 'DoctorController@getPatientDentalTestsForDoctor'));
        Route::get('{doctorId}/patient/{patientId}/xraytests', array('as' => 'doctor.xraytests', 'uses' => 'DoctorController@getPatientXrayTestsForDoctor'));


//Receipt
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/labtestreceipts', array('as' => 'patient.labtestreceipts', 'uses' => 'DoctorController@getLabTestDetailsForReceiptForDoctor'));
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/receiptdetails', array('as' => 'patient.receiptdetails', 'uses' => 'DoctorController@getPatientReceiptDetailsForDoctor'));

        Route::post('savelabreceipts', array('as' => 'patient.savelabreceipts', 'uses' => 'DoctorController@saveLabReceiptDetailsForPatientForDoctor'));
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/labreceipts', array('as' => 'patient.labreceipts', 'uses' => 'DoctorController@getLabReceiptsByPatientForDoctor'));


//All Print
        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/print', array('as' => 'hospital.patientprintdetails', 'uses' => 'DoctorController@PatientPrintDetailsByHospitalForDoctor'));
    });


    Route::group(['namespace' => 'Pharmacy'], function() {
        Route::get('rest/api/{doctorId}/hospital/{hospitalId}/prescriptions', array('as' => 'doctor.prescriptions', 'uses' => 'PharmacyController@getPrescriptionListForDoctor'));
        Route::get('rest/api/prescription/{prescriptionId}', array('as' => 'doctor.prescriptiondetails', 'uses' => 'PharmacyController@getPrescriptionDetailsForDoctor'));
        Route::get('rest/api/prescription', array('as' => 'doctor.searchbyprid', 'uses' => 'PharmacyController@getPrescriptionByPrid'));


        Route::get('prescription/{prescriptionId}', array('as' => 'doctor.prescriptiondetails', 'uses' => 'PharmacyController@getPrescriptionDetailsForDoctor'));
        //Route::get('rest/api/patient/{prescriptionId}/mail', array('as' => 'patient.sendemail', 'uses' => 'PharmacyController@forwardPrescriptionDetailsByMail'));
        //Route::get('rest/api/patient/{prescriptionId}/sms', array('as' => 'patient.send sms', 'uses' => 'PharmacyController@forwardPrescriptionDetailsBySMS'));
        //Route::get('rest/api/{labId}/changepassword', array('as' => 'lab.changepassword', 'uses' => 'PharmacyController@editChangePassword'));
        //Route::post('rest/api/pharmacy', array('as' => 'pharmacy.editpharmacy', 'uses' => 'PharmacyController@editPharmacy'));
        //Route::get('rest/api/lab', array('as' => 'lab.editlab', 'uses' => 'PharmacyController@saveChangesPassword'));
    });


    Route::group(['namespace' => 'Lab'], function() {
        Route::get('rest/api/{doctorId}/hospital/{hospitalId}/labtests', array('as' => 'doctor.labtests', 'uses' => 'LabController@getTestsForDoctor'));
        Route::get('rest/api/lab/{labTestId}', array('as' => 'doctor.labdetails', 'uses' => 'LabController@getLabTestDetailsForDoctor'));
        Route::get('rest/api/labtests', array('as' => 'doctor.lid', 'uses' => 'LabController@getLabTestsByLid'));

        Route::get('{doctorId}/hospital/{hospitalId}/patient/{patientId}/lab-report-download', array('as' => 'lab.getlabtestreports', 'uses' => 'LabController@getPatientDocumentsForDoctor'));
        //Route::get('rest/api/patient/{prescriptionId}/mail', array('as' => 'patient.sendemail', 'uses' => 'LabController@forwardLabDetailsByMail'));
        //Route::get('rest/api/patient/{prescriptionId}/sms', array('as' => 'patient.send sms', 'uses' => 'LabController@forwardLabDetailsBySMS'));
        //Route::get('rest/api/{labId}/changepassword', array('as' => 'lab.changepassword', 'uses' => 'LabController@editChangePassword'));
        //Route::post('rest/api/lab', array('as' => 'lab.editlab', 'uses' => 'LabController@editLab'));
        //Route::get('rest/api/lab', array('as' => 'lab.editlab', 'uses' => 'LabController@saveChangesPassword'));
    });
});


Route::group(['prefix' => 'lab'], function() {
    Route::get('{id}/dashboard', function ($id) {
        return redirect('lab/rest/api/' . $id . '/profile');
        //return view('portal.lab-dashboard');
    });

    /*
      Route::get('{id}/dashboard', function () {
      return view('portal.lab-dashboard');
      });
     */

    Route::group(['namespace' => 'Lab'], function() {
        Route::get('rest/api/{labId}/profile', array('as' => 'lab.viewprofile', 'uses' => 'LabController@getProfile'));
        Route::get('rest/api/{labId}/hospital/{hospitalId}/patients', array('as' => 'lab.patients', 'uses' => 'LabController@getPatientListForLab'));
        Route::get('rest/api/{labId}/hospital/{hospitalId}/labtests', array('as' => 'lab.labtests', 'uses' => 'LabController@getTestsForLab'));
        Route::get('rest/api/labtests', array('as' => 'lab.lid', 'uses' => 'LabController@getLabTestsByLid'));

        Route::get('rest/api/{labId}/editprofile', array('as' => 'lab.editprofile', 'uses' => 'LabController@editProfile'));
        //Route::post('rest/api/lab', array('as' => 'lab.editlab', 'uses' => 'LabController@editLab'));
        Route::post('rest/api/lab', array('as' => 'lab.editlab', 'uses' => 'LabController@editLab'));
        Route::get('rest/api/lab/{labId}', array('as' => 'lab.labdetails', 'uses' => 'LabController@getLabTestDetails'));

        Route::get('rest/api/patient/{prescriptionId}/mail', array('as' => 'patient.sendemail', 'uses' => 'LabController@forwardLabDetailsByMail'));
        Route::get('rest/api/patient/{prescriptionId}/sms', array('as' => 'patient.send sms', 'uses' => 'LabController@forwardLabDetailsBySMS'));


        //WEB NEW PAGES
        Route::get('{labId}/hospital/{hid}/patient/{patientId}/details', array('as' => 'lab.patientdetails', 'uses' => 'LabController@PatientDetailsForLab'));
        Route::get('{labId}/hospital/{hid}/patient/{patientId}/lab-details', array('as' => 'lab.patientlabdetails', 'uses' => 'LabController@PatientLabDetailsForLab'));
        Route::get('{labId}/hospital/{hid}/patient/{patientId}/lab-report-upload', array('as' => 'lab.patientlabreportupload', 'uses' => 'LabController@PatientLabReportUploadForLab'));
        Route::post('{labId}/hospital/{hid}/patient/{patientId}/labreports', array('as' => 'patient.labtestreports', 'uses' => 'LabController@uploadPatientLabDocuments'));
        Route::get('{labId}/hospital/{hid}/patient/{patientId}/lab-report-download', array('as' => 'lab.getlabtestreports', 'uses' => 'LabController@getPatientDocuments'));
        Route::get('rest/labreports/{itemId}/report', array('as' => 'patient.downloadreport', 'uses' => 'LabController@downloadPatientDocument'));
        /* Srart of test URLS */
        Route::get('{labId}/hospital/{hid}/patient/{patientId}/lab-report-entry', array('as' => 'lab.patientlabreportupload', 'uses' => 'LabController@PatientLabReportEntryForLab'));

        Route::post('{labId}/hospital/{hid}/patient/{patientId}/lab-report-entry', array('as' => 'patient.labtestreports', 'uses' => 'LabController@uploadPatientLabDocumentsnew'));

        /* End of test URLS */
        Route::get('rest/api/lab/report/{labTestId}/upload', array('as' => 'patient.labtestupload', 'uses' => 'LabController@getLabTestUploadForLab'));
        Route::post('rest/api/lab/report/{labTestId}/saveupload', array('as' => 'patient.labtestsaveupload', 'uses' => 'LabController@getLabTestUploadSaveForLab'));



        Route::post('rest/labreports', array('as' => 'patient.labtestreports', 'uses' => 'LabController@uploadPatientLabDocuments'));
        Route::get('rest/patient/{patientId}/labreports', array('as' => 'patient.getlabtestreports', 'uses' => 'LabController@getPatientDocuments'));
        //Route::get('rest/labreports/{itemId}/report', array('as' => 'patient.downloadreport', 'uses' => 'LabController@downloadPatientDocument'));
        //Route::get('rest/api/{labId}/changepassword', array('as' => 'lab.changepassword', 'uses' => 'LabController@editChangePassword'));
        //Route::post('rest/api/lab', array('as' => 'lab.editlab', 'uses' => 'LabController@editLab'));
        //Route::get('rest/api/lab', array('as' => 'lab.editlab', 'uses' => 'LabController@saveChangesPassword'));
    });
});

Event::listen('illuminate.query', function($query) {
    var_dump($query);
});
