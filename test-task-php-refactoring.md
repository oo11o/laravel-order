# Тестове завдання для Middle PHP Developer

## Загальна інформація
Вам надано фрагмент коду Laravel-контролера для створення замовлень в інтернет-магазині. Код працює, але містить проблеми з якістю, безпекою та продуктивністю.

## Проблемний код для рефакторингу

```php
// OrderController.php
class OrderController extends Controller
{
    public function store(Request $request):void
    {
        if (!$request->product_id || !$request->quantity) {
            return response("Validation error", 400);
        }
        
        $product = DB::select("SELECT * FROM products WHERE id = " . $request->product_id)[0];
        if (!$product) {
            return response("Product not found", 404);
        }
        
        $order = new Order;
        $order->product_id = $request->product_id;
        $order->user_id = auth()->id() ?? 1; // Проблема безпеки
        $order->quantity = $request->quantity;
        $order->price = $product->price * $request->quantity;
        $order->status = 'new';
        $order->save();
      
        
        Mail::send('emails.order_confirmation', ['order' => $order], function($message) {
            $message->to(auth()->user()->email)->subject('Order Confirmation');
        });
        
        $previousOrders = Order::where('user_id', auth()->id())->get();
        foreach($previousOrders as $prevOrder) {
            $productDetails[] = $prevOrder->product->name;
        }
        
        return response()->json(['order_id' => $order->id, 'previous_orders' => $productDetails]);
    }
}
```

## Структура моделей (для довідки)

```php
// Product.php
class Product extends Model
{
    protected $fillable = ['name', 'price', 'description', 'stock'];
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

// Order.php
class Order extends Model
{
    protected $fillable = ['product_id', 'user_id', 'quantity', 'price', 'status'];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

## Завдання
1. Відрефакторити існуючий метод `store`
2. Додати один новий метод до контролера

### Частина 1: Рефакторинг методу store

Необхідно виправити наступні проблеми:

1. **Валідація даних**:
   - Замінити примітивну валідацію на стандартний підхід Laravel

2. **Безпека**:
   - Усунути вразливість до SQL-ін'єкції
   - Виправити проблему з `user_id`

3. **Архітектура**:
   - Відокремити бізнес-логіку від контролера (на ваш вибір)
   - Зробити відправку email асинхронною (через queue)

4. **Продуктивність**:
   - Вирішити проблему N+1 при завантаженні попередніх замовлень

### Частина 2: Додавання нового методу

Додати **ОДИН** з наступних методів (на ваш вибір):

1. **Отримання деталей замовлення**:
   - Метод `show(Request $request, $id)` для отримання інформації про замовлення
   - Базова авторизація (куористувач бачить лише свої замовлення)

АБО

2. **Оновлення статусу замовлення**:
   - Метод `updateStatus(Request $request, $id)` для зміни статусу замовлення
   - Валідація статусу (можливі значення: 'new', 'processing', 'shipped', 'delivered', 'cancelled')

## Технічні вимоги

1. **Використовувати стандартні практики Laravel**:
   - Form Request або валідація в контролері
   - Eloquent ORM замість raw SQL
   - API Resources для форматування відповідей (опціонально)

2. **Забезпечити безпеку**:
   - Запобігти SQL-ін'єкціям
   - Додати базову авторизацію

3. **Оптимізувати продуктивність**:
   - Вирішити проблему N+1
   - Асинхронна відправка email

## Очікувані результати
1. Відрефакторований код методу `store`
2. Один новий метод на ваш вибір
3. Короткий коментар про найважливіші зміни, які ви зробили (2-3 речення)

## Критерії оцінки
1. Якість коду та виправлення проблем
2. Рішення проблем безпеки
3. Оптимізація запитів
4. Правильне використання Laravel практик
5. Реалізація нового функціоналу
