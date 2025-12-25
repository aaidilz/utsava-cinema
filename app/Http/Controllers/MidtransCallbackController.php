use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
    public function handle(Request $request): JsonResponse
    {
        $midtrans = app()->make('App\Services\MidtransService');
        $payload = $request->all();
