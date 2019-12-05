# Usage

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