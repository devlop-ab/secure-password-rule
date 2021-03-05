<p align="center">
    <a href="https://packagist.org/packages/devlop/secure-password-rule"><img src="https://img.shields.io/packagist/v/devlop/secure-password-rule" alt="Latest Stable Version"></a>
    <a href="https://github.com/devlop/secure-password-rule/blob/master/LICENSE.md"><img src="https://img.shields.io/packagist/l/devlop/secure-password-rule" alt="License"></a>
</p>

# SecurePasswordRule

An extendable password validation rule for Laravel to make it easy to have the same password requirements across the whole system.

The initial settings are very permissive and pretty much only checks the length of the password, see ```Configuration``` for how to
change it for your needs.

# Installation

```bash
composer require devlop/secure-password-rule
```

# Usage

Add it to the ```rules``` of a ```FormRequest```

```php
namespace App\Http\Requests;

use Devlop\SecurePasswordRule\SecurePasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'new_password' => [
                'required',
                'string',
                new SecurePasswordRule,
            ],
        ];
    }
}
```

# Configuration

The class is open for extension and does not accept any arguments when instantiating since that would open the possibility of
ending up with different password requirements in different parts of your system.

The recommended way is to create your own sub class of SecurePasswordRule and change the parameters you wish to change, and then
reference that sub class instead in your FormRequests.

```php
namespace App\Rules;

use Devlop\SecurePasswordRule\SecurePasswordRule as BaseSecurePasswordRule;

class SecurePasswordRule extends BaseSecurePasswordRule
{
    /**
     * Require the use of X special characters
     */
    protected int $requireSpecial = 10;
}
```
