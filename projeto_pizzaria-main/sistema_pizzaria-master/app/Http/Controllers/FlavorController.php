<?php

namespace App\Http\Controllers;

use App\Http\Enums\TamanhoEnum;
use App\Models\Flavor;
use App\Http\Requests\{
    FlavorCreatRequest
};
use Illuminate\Http\Request;

/**
 * Class FlavorController
 *
 * @package App\Http\Controllers
 * @author Vinícius Siqueira
 * @link https://github.com/ViniciusSCS
 * @date 2024-10-01 15:52:04
 * @copyright UniEVANGÉLICA
 */
class FlavorController extends Controller
{
    protected $flavorService;

    public function __construct()
    {
        $this->flavorService = new FlavorService();
    }

    public function index()
    {
        $flavors = $this->flavorService->getAllFlavors();

        return [
            'status' => 200,
            'message' => 'Sabores encontrados!!',
            'sabores' => $flavors,
        ];
    }

    public function store(FlavorRequest $request)
    {
        $flavor = $this->flavorService->createFlavor($request->validated());

        return [
            'status' => 200,
            'message' => 'Sabor cadastrado com sucesso!!',
            'sabor' => $flavor,
        ];
    }

    public function show(string $id)
    {
        $flavor = $this->flavorService->getFlavorById($id);

        if (!$flavor) {
            return [
                'status' => 404,
                'message' => 'Sabor não encontrado! Que triste!',
            ];
        }

        return [
            'status' => 200,
            'message' => 'Sabor encontrado com sucesso!!',
            'sabor' => $flavor,
        ];
    }

    public function update(FlavorRequest $request, string $id)
    {
        $flavor = $this->flavorService->updateFlavor($id, $request->validated());

        if (!$flavor) {
            return [
                'status' => 404,
                'message' => 'Sabor não encontrado! Que triste!',
            ];
        }

        return [
            'status' => 200,
            'message' => 'Sabor atualizado com sucesso!!',
            'sabor' => $flavor,
        ];
    }

    public function destroy(string $id)
    {
        $deleted = $this->flavorService->deleteFlavor($id);

        if (!$deleted) {
            return [
                'status' => 404,
                'message' => 'Sabor não encontrado! Que triste!',
            ];
        }

        return [
            'status' => 200,
            'message' => 'Sabor deletado com sucesso!!',
        ];
    }
}

// Sabor
class FlavorService
{
    public function getAllFlavors()
    {
        return Flavor::select('id', 'sabor', 'preco', 'tamanho')->paginate(10);
    }

    public function createFlavor(array $data)
    {
        return Flavor::create([
            'sabor' => $data['sabor'],
            'preco' => $data['preco'],
            'tamanho' => TamanhoEnum::from($data['tamanho']),
        ]);
    }

    public function getFlavorById(string $id)
    {
        return Flavor::find($id);
    }

    public function updateFlavor(string $id, array $data)
    {
        $flavor = Flavor::find($id);

        if ($flavor) {
            $flavor->update($data);
            return $flavor;
        }

        return null;
    }

    public function deleteFlavor(string $id)
    {
        $flavor = Flavor::find($id);

        if ($flavor) {
            $flavor->delete();
            return true;
        }

        return false;
    }
}

// Classe de validação
class FlavorRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Ajuste conforme necessário
    }

    public function rules()
    {
        return [
            'sabor' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'tamanho' => 'required|in:pequeno,médio,grande', // Ajuste os tamanhos 
        ];
    }
}