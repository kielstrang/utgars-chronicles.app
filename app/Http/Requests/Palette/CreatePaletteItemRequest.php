<?php declare(strict_types=1);

namespace App\Http\Requests\Palette;

use App\PaletteType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreatePaletteItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in([PaletteType::YES, PaletteType::NO])],
        ];
    }

    public function name(): string
    {
        return $this->get('name');
    }

    public function type(): string
    {
        return $this->get('type');
    }
}
