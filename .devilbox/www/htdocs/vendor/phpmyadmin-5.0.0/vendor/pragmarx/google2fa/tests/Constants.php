<?php

namespace PragmaRX\Google2FA\Tests;

class Constants
{
    const SECRET = 'ADUMJO5634NPDEKW';

    const SHORT_SECRET = 'ADUMJO5';

    const INVALID_SECRET = 'DUMJO5634NPDEKX@';

    const WRONG_SECRET = 'ADUMJO5634NPDEKX';

    const URL = 'https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=otpauth%3A%2F%2Ftotp%2FPragmaRX%3Aacr%252Bpragmarx%2540antoniocarlosribeiro.com%3Fsecret%3DADUMJO5634NPDEKW%26issuer%3DPragmaRX';
}
