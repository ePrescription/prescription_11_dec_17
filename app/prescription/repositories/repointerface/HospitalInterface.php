<?php namespace App\prescription\repositories\repointerface;
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/8/2016
 * Time: 5:07 PM
 */

use App\Http\ViewModels\DoctorReferralsViewModel;
use App\Http\ViewModels\FeeReceiptViewModel;
use App\Http\ViewModels\NewAppointmentViewModel;
use App\Http\ViewModels\PatientComplaintsViewModel;
use App\Http\ViewModels\PatientDentalViewModel;
use App\Http\ViewModels\PatientDiagnosisViewModel;
use App\Http\ViewModels\PatientDrugHistoryViewModel;
use App\Http\ViewModels\PatientFamilyIllnessViewModel;
use App\Http\ViewModels\PatientGeneralExaminationViewModel;
use App\Http\ViewModels\PatientLabDocumentsViewModel;
use App\Http\ViewModels\PatientLabReceiptViewModel;
use App\Http\ViewModels\PatientLabTestViewModel;
use App\Http\ViewModels\PatientPastIllnessViewModel;
use App\Http\ViewModels\PatientPersonalHistoryViewModel;
use App\Http\ViewModels\PatientPrescriptionViewModel;
use App\Http\ViewModels\PatientProfileViewModel;

use App\Http\ViewModels\PatientPregnancyViewModel;
use App\Http\ViewModels\PatientScanViewModel;
use App\Http\ViewModels\PatientSymptomsViewModel;
use App\Http\ViewModels\PatientUrineExaminationViewModel;
use App\Http\ViewModels\PatientXRayViewModel;

interface HospitalInterface {
    public function getHospitals();
    public function getHospitalByKeyword($keyword = null);
    public function getHospitalId($userTypeId, $userId);
    public function getDoctorsByHospitalId($hospitalId);

    public function getHospitalsForDoctor($email);
    public function getHospitalsByDoctorId($doctorId);

    public function getDoctorDetails($doctorId);

    //Get Appointment details
    public function getAppointmentsByHospitalAndDoctor($hospitalId, $doctorId);
    public function saveNewAppointment(NewAppointmentViewModel $appointmentVM);

    public function cancelAppointment($appointmentId);
    public function transferAppointment($appointmentId, $doctorId);

    //Get Patient List
    public function getPatientsByHospital($hospitalId, $keyword = null);

    public function getPatientsByHospitalAndDoctor($hospitalId, $doctorId);
    //public function getPatientsByHospital($hospitalId);
    public function getPatientDetailsById($patientId);
    public function getPatientProfile($patientId);
    public function getPatientAppointments($patientId);
    public function getDashboardDetails($hospitalId, $selectedDate);
    public function getFutureAppointmentsForDashboard($fromDate, $toDate, $hospitalId, $doctorId = null);

    public function getDashboardDetailsForDoctor($hospitalId, $doctorId);
    public function getPatientsByAppointmentCategory($hospitalId, $categoryType, $doctorId = null);
    public function getPatientsByAppointmentDate($hospitalId, $doctorId, $appointmentDate);

    public function getPatientAppointmentDates($patientId, $hospitalId);
    //public function getPatientsByA

    public function getPatientAppointmentsByHospital($patientId, $hospitalId);
    public function getAppointmentDetails($appointmentId);

    //Get Prescription List
    public function getPrescriptions($hospitalId, $doctorId);
    public function getPrescriptionByPatient($patientId);
    public function getPrescriptionDetails($prescriptionId);
    public function savePatientPrescription(PatientPrescriptionViewModel $patientPrescriptionVM);
    public function savePatientProfile(PatientProfileViewModel $patientProfileVM);
    public function editPatientProfile(PatientProfileViewModel $patientProfileVM);

    public function checkIsNewPatient($hospitalId, $doctorId, $patientId);

    //Search Patient
    public function searchPatientByName($keyword);
    public function searchPatientByPid($pid);

    public function searchByPatientByPidOrName($keyWord = null);
    public function searchPatientByHospitalAndName($hospitalId, $keyword = null);

    //Drug list
    public function getTradeNames($keyword);
    public function getFormulationNames($keyword);

    //Lab Tests
    public function getLabTests($keyword);
    public function getLabTestsForPatient($hospitalId, $doctorId);
    public function getLabTestsByPatient($patientId);
    public function getLabTestDetails($labTestId);
    public function savePatientLabTests(PatientLabTestViewModel $patientLabTestVM);

    //Hospital
    public function getProfile($hospitalId);
    public function getDoctorNames($hospitalId, $keyword);
    public function getPatientNames($hospitalId, $keyword);

    //Fee receipt
    public function getFeeReceipts($hospitalId, $doctorId);
    public function getFeeReceiptsByPatient($patientId);
    public function getReceiptDetails($receiptId);
    public function saveFeeReceipt(FeeReceiptViewModel $feeReceiptVM);

    //Symptoms
    public function getMainSymptoms();
    public function getSubSymptomsForMainSymptoms($mainSymptomsId);
    public function getSymptomsForSubSymptoms($subSymptomId);
    public function getPersonalHistory($patientId, $personalHistoryDate);
    public function getPatientPastIllness($patientId, $pastIllnessDate);
    public function getPatientFamilyIllness($patientId, $familyIllnessDate);
    public function savePersonalHistory(PatientPersonalHistoryViewModel $patientHistoryVM);
    public function getPatientGeneralExamination($patientId, $generalExaminationDate);
    public function savePatientGeneralExamination(PatientGeneralExaminationViewModel $patientExaminationVM);
    public function savePatientPastIllness(PatientPastIllnessViewModel $patientPastIllnessVM);
    public function savePatientFamilyIllness(PatientFamilyIllnessViewModel $patientFamilyIllnessVM);

    public function getExaminationDates($patientId, $hospitalId);
    public function getLatestAppointmentDateForPatient($patientId, $hospitalId);
    //;

    public function getPregnancyDetails($patientId, $pregnancyDate = null);
    public function savePatientPregnancyDetails(PatientPregnancyViewModel $patientPregnancyVM);
    public function getPatientScanDetails($patientId, $scanDate);
    public function savePatientScanDetails(PatientScanViewModel $patientScanVM);
    public function getPatientSymptoms($patientId, $symptomDate);
    public function savePatientSymptoms(PatientSymptomsViewModel $patientSymVM);

    public function getPatientDrugHistory($patientId);
    public function savePatientDrugHistory(PatientDrugHistoryViewModel $drugHistoryVM);

    public function getPatientUrineTests($patientId, $urineTestDate);
    public function savePatientUrineTests(PatientUrineExaminationViewModel $patientUrineVM);

    public function getPatientMotionTests($patientId, $motionTestDate);
    public function savePatientMotionTests(PatientUrineExaminationViewModel $patientMotionVM);

    public function getPatientBloodTests($patientId, $bloodTestDate);
    public function savePatientBloodTests(PatientUrineExaminationViewModel $patientBloodVM);
    public function savePatientBloodTessNew(PatientUrineExaminationViewModel $patientBloodVM);

    public function getPatientUltraSoundTests($patientId, $ultraSoundDate);
    public function savePatientUltraSoundTests(PatientUrineExaminationViewModel $patientUltraSoundVM);

    public function getPatientDentalTests($patientId, $dentalDate);
    public function savePatientDentalTests(PatientDentalViewModel $patientDentalVM);

    public function getPatientXrayTests($patientId, $dentalDate);
    public function savePatientXRayTests(PatientXRayViewModel $patientXRayVM);

    public function getAllFamilyIllness();
    public function getAllPastIllness();
    public function getAllGeneralExaminations();
    public function getAllPersonalHistory();
    public function getAllPregnancy();
    public function getAllScans();
    public function getAllDentalItems();
    public function getAllXRayItems();

    public function getPatientLabTests($hospitalId, $patientId, $feeStatus = null);
    public function getLabTestDetailsByPatient($labTestType, $labTestId);

    public function getPatientReceiptDetails($hospitalId, $patientId, $feeReceiptId);
    public function getLabTestDetailsForReceipt($patientId, $hospitalId, $generatedDate = null);
    public function saveLabReceiptDetailsForPatient(PatientLabReceiptViewModel $labReceiptsVM);
    public function getLabReceiptsByPatient($patientId, $hospitalId);

    public function getAllSpecialties();
    public function getDoctorsBySpecialty($specialtyId);
    public function saveReferralDoctor(DoctorReferralsViewModel $doctorReferralsVM);
    public function getReferralDoctorDetails($referralId);

    //
    public function getComplaintTypes();
    public function getComplaints($complaintTypeId);
    public function savePatientComplaints(PatientComplaintsViewModel $patientComVM);
    public function getPatientComplaints($patientId, $complaintsDate);

    public function savePatientInvestigationAndDiagnosis(PatientDiagnosisViewModel $patientDiagnosisVM);
    public function getPatientInvestigations($patientId, $investigationDate);

    public function uploadPatientLabDocuments(PatientLabDocumentsViewModel $labDocumentsVM);

}