<?php

namespace App\Enum;

enum KYCRequestStatus : string
{
    const PENDING = 'pending';
    const SUCCESSFUL = 'approved';
    const FAILED = 'rejected';
    const RE_SUBMITTED = 're-submitted';
}
