---
name: laravel-api-resource-generator
description: This skill should be used when the user asks to "create a model with API", "scaffold an API resource", "generate CRUD for a model", "yeni model oluştur", "API endpoint oluştur", or gives a model name with a list of fields and expects a complete Laravel API implementation. Generates model, migration, factory, seeder entry, form request, API resource, controller, routes, and Pest tests following the project's established conventions.
---

# Laravel API Resource Generator

Generate a complete, production-shaped API resource for a Laravel 12/13 project,
following the exact conventions used in this codebase.

## When to use

Trigger when the user provides:

- A model name (singular, StudlyCase), and
- A list of fields with types, and
- An expectation that "everything else" is created.

Example request: "Create an Author model with name (string) and bio (text)."

## Before generating: confirm scope

Ask ONLY if genuinely ambiguous. Do not interrogate the user. Specifically:

1. **Check for conflicts first.** Run `ls app/Models/` and `php artisan route:list --path={plural}`.
   If the model or route already exists, STOP and tell the user instead of overwriting.
2. If a relationship is implied (e.g. a `category_id` field), confirm the related model exists.
3. If the user did not mention caching, do NOT create an Observer. Only add caching when asked.
4. Default assumptions (state them, don't ask): API-only, v1 namespace, Sanctum-protected,
   paginated index, Pest tests.

## Generation order

Create files in this exact order. Verify each step before moving on.

### Step 1 — Model, migration, factory

```bash
php artisan make:model {Model} -mf
```

**Migration** (`database/migrations/*_create_{plural}_table.php`) — fill `up()`:

```php
public function up(): void
{
    Schema::create('{plural}', function (Blueprint $table) {
        $table->id();
        // foreign keys first, if any:
        // $table->foreignId('category_id')->constrained();
        $table->string('name');
        $table->text('description')->nullable();
        $table->timestamps();
    });
}
```

Rules:

- Nullable for optional text fields.
- Money values stored as `integer` (cents), never `float` or `decimal`.
- Foreign keys via `foreignId(...)->constrained()`.

**Model** (`app/Models/{Model}.php`):

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class {Model} extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    // Include BOTH directions of any relationship:
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
```

Rules:

- `$fillable` lists every user-writable column. NEVER include foreign keys that
  should be set via relationship methods, unless the API genuinely accepts them.
- Always type-hint relationship return types (`BelongsTo`, `HasMany`).
- If this model is referenced by another, add the inverse `hasMany()` on that model too —
  a one-directional relationship silently breaks `->exists()` checks later.
- **When adding an inverse relationship to an EXISTING model** (because a new
  dependent model just references it), also check that model's existing
  `destroy()` method. If it has no guard for the new relation, add one —
  do not just add the relationship and leave deletion unsafe. If you choose
  not to add the guard because the user didn't ask, say so explicitly in
  the final report (don't silently skip it).

**Factory** (`database/factories/{Model}Factory.php`):

```php
public function definition(): array
{
    return [
        'name' => fake()->words(asText: true),
        'description' => fake()->paragraph(),
        // for a relation: 'category_id' => Category::inRandomOrder()->first()->id,
    ];
}
```

**Seeder** — add to `database/seeders/DatabaseSeeder.php` `run()`:

```php
{Model}::factory(10)->create();
```

Add the `use App\Models\{Model};` import. Do NOT create a separate seeder file.

### Step 2 — Form Request

```bash
php artisan make:request Store{Model}Request
```

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Store{Model}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter a name for the {model}.',
        ];
    }
}
```

Rules:

- One Store request reused for both `store()` and `update()` unless rules genuinely differ.
- Array syntax for rules (`['required', 'string']`), not pipe strings.
- File fields: `['nullable', 'file']`.

### Step 3 — API Resource

```bash
php artisan make:resource {Model}Resource
```

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class {Model}Resource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            // relation, only when eager loaded:
            'category' => CategoryResource::make($this->whenLoaded('category')),
            // money: number_format($this->price / 100, 2)
        ];
    }
}
```

Rules:

- NEVER expose `created_at`/`updated_at` unless the user asks — the API contract
  should be deliberate, not a database dump.
- NEVER expose `password`, `remember_token`, or any token column.
- Relations ALWAYS via `whenLoaded()`, never bare `$this->relation` — a bare access
  silently triggers N+1 when the controller forgets `with()`.
- Avoid deciding index-vs-show formatting by string-matching the request path
  (`$request->is('api/...')`). It breaks the moment a route prefix changes.
  If different shapes are needed, create a separate `{Model}IndexResource`.

### Step 4 — Controller

```bash
php artisan make:controller Api/V1/{Model}Controller
```

```php
<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store{Model}Request;
use App\Http\Resources\{Model}Resource;
use App\Models\{Model};

/**
 * @group {Plural}
 *
 * Managing {plural}
 */
class {Model}Controller extends Controller
{
    /**
     * Get {plural}
     *
     * @queryParam page Which page to show. Example: 2
     */
    public function index()
    {
        ${plural} = {Model}::with('category')->paginate(15);

        return {Model}Resource::collection(${plural});
    }

    /**
     * POST {plural}
     *
     * @bodyParam name string required Name of the {model}. Example: Example name
     */
    public function store(Store{Model}Request $request)
    {
        ${model} = {Model}::create($request->validated());

        return new {Model}Resource(${model});
    }

    public function show({Model} ${model})
    {
        return new {Model}Resource(${model}->load('category'));
    }

    public function update({Model} ${model}, Store{Model}Request $request)
    {
        ${model}->update($request->validated());

        return new {Model}Resource(${model});
    }

    public function destroy({Model} ${model})
    {
        // Guard against foreign key constraint failures:
        if (${model}->products()->exists()) {
            return response()->json([
                'message' => 'This {model} has related records. Remove or reassign them first.',
            ], 409);
        }

        ${model}->delete();

        return response()->noContent();
    }
}
```

Rules:

- Route model binding (`{Model} ${model}`) instead of manual `find()`/404 handling.
- Eager load in `index()` whenever the Resource exposes a relation — `with()` and
  `whenLoaded()` are a pair; one without the other is either N+1 or missing data.
- Status codes: Resource return gives 201 on store automatically; `noContent()` = 204;
  a blocked delete = 409; validation failure = 422 (automatic via Form Request).
- If a related model exists, include the `->exists()` guard in `destroy()`.
  Otherwise a database-level constraint error surfaces as a 500 and leaks SQL.
- This applies retroactively too: if THIS generation step just created a new
  inverse relationship on an already-existing model (see Step 1), that
  existing model's controller needs its `destroy()` revisited — not just
  the new model's controller.
- Scribe docblocks (`@group`, `@queryParam`, `@bodyParam`) on public endpoints.

### Step 5 — Routes

Add to `routes/api.php`, INSIDE the existing `auth:sanctum` group and `v1` prefix group:

```php
use App\Http\Controllers\Api\V1\{Model}Controller;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('v1')->group(function () {
        // ... existing routes
        Route::apiResource('{plural}', {Model}Controller::class);
    });
});
```

Rules:

- `apiResource`, never `resource` — API has no create/edit form pages.
- Never place a new resource outside the auth group unless the user explicitly
  says it should be public.
- Read the existing `routes/api.php` first and insert into the existing structure.
  Do not restructure or reorder unrelated routes.

### Step 6 — Tests (Pest)

```bash
php artisan make:test Api/{Model}Test --pest
```

```php
<?php

use App\Models\User;
use App\Models\{Model};
use Illuminate\Support\Arr;

test('api returns {plural} list', function () {
    $user = User::factory()->create();
    ${model} = {Model}::factory()->create();

    $this->actingAs($user)
        ->getJson(route('{plural}.index'))
        ->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJson([
            'data' => [Arr::only(${model}->toArray(), ['id', 'name'])],
        ]);
});

test('api {model} store successful', function () {
    ${model} = ['name' => 'Test {Model}', 'description' => 'Test description'];
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('{plural}.store'), ${model})
        ->assertStatus(201)
        ->assertJson([
            'data' => Arr::only(${model}, ['name']),
        ]);
});

test('api {model} store fails validation', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('{plural}.store'), [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

test('guests cannot access {plural}', function () {
    $this->getJson(route('{plural}.index'))
        ->assertStatus(401);
});
```

Rules:

- Confirm `tests/Pest.php` has `->use(RefreshDatabase::class)` uncommented for `Feature`.
- Use named routes (`route('{plural}.index')`), never hardcoded URL strings —
  a prefix change should not break tests.
- Use `getJson`/`postJson`, not `get`/`post` — they set the JSON Accept header.
- Compare with `Arr::only(...)` limited to fields the Resource actually exposes.
- Always include the unauthenticated (401) test — protection is easy to forget.

### Step 7 — Optional: caching + Observer

ONLY when the user explicitly asks for caching.

```php
// Controller index()
return {Model}Resource::collection(
    cache()->rememberForever('{plural}', fn () => {Model}::with('category')->get())
);
```

```bash
php artisan make:observer {Model}Observer --model={Model}
```

```php
class {Model}Observer
{
    public function created({Model} ${model}): void
    {
        cache()->forget('{plural}');
    }

    public function updated({Model} ${model}): void
    {
        cache()->forget('{plural}');
    }

    public function deleted({Model} ${model}): void
    {
        cache()->forget('{plural}');
    }
}
```

```php
// Model
#[ObservedBy([{Model}Observer::class])]
class {Model} extends Model
```

Caching and pagination do not combine cleanly — if the user wants both,
flag the conflict rather than silently picking one.

## Step 8 — Verify

Run, in order, and report the output of each:

```bash
php artisan migrate
php artisan route:clear
php artisan route:list --path={plural}
php artisan test --filter={Model}
```

If migration or tests fail, fix the cause and re-run. Do not report success
on unverified code.

## Final report

Summarize as a short list:

- Files created (with paths)
- Routes registered (method + URI)
- Test results (pass/fail counts)
- Any assumption made that the user may want to change
- Anything deliberately NOT done (e.g. "no Observer created — caching wasn't requested")

## Hard rules

- Read existing files before editing them. Never blindly overwrite.
- Never modify unrelated code, routes, or configuration.
- Never expose credentials, tokens, or hashed passwords in a Resource.
- Never add a route outside the auth group without saying so explicitly.
- Never claim tests pass without running them.
- When project convention and general Laravel convention disagree,
  follow the project.
