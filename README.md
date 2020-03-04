# Nova Tree View

Display a model in a Tree View with drag and drop functionality.
Useful for category structure.

## Setup

Make sure your model you wish to use in the tree structure has the following columns:
```
parent_id
is_active
order
```

Add the tool to `NovaServiceProvider.php`


## Category Model Example:

```
Schema::create('categories', function (Blueprint $table) {
    $table->unsignedInteger('id')->autoIncrement();
    $table->string('title');
    $table->string('slug');
    $table->unsignedInteger('parent_id')->nullable();
    $table->boolean('is_active')->default(true);
    $table->tinyInteger('order')->default(0);
});
```

## Usage

```
/app/Providers/NovaServiceProvider.php
...
use Day4\TreeView\TreeView;
    ...
    public function tools()
    {
        return [
            ...
            new TreeView([
                ['Categories', 'categories']
            ])
        ];
    }
```