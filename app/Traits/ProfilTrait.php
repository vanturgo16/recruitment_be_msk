<?php

namespace App\Traits;

// Model
use App\Models\MainProfile;
use App\Models\EducationInfo;
use App\Models\GeneralInfo;
use App\Models\WorkExpInfo;
use App\Models\JobApply;

trait ProfilTrait
{
    public function checkProfile($idCandidate)
    {
        $hasMainProfile = MainProfile::where('id_candidate', $idCandidate)->exists();
        $hasEducationInfo = EducationInfo::where('id_candidate', $idCandidate)->exists();
        $generalInfo = GeneralInfo::where('id_candidate', $idCandidate)->first();
        $hasGeneralInfo = $generalInfo !== null;

        if ($hasGeneralInfo) {
            $isFreshGraduate = $generalInfo->experience === 'Fresh Graduate';
            $hasWorkExpInfo = $isFreshGraduate || WorkExpInfo::where('id_candidate', $idCandidate)->exists();
        } else {
            $hasWorkExpInfo = false;
        }

        $isProfileComplete = $hasMainProfile && $hasEducationInfo && $hasGeneralInfo && $hasWorkExpInfo;
        return $isProfileComplete;
    }

    public function checkApplicationIP($idCandidate)
    {
        return JobApply::where('id_candidate', $idCandidate)->where('status', 0)->exists();
    }
}
