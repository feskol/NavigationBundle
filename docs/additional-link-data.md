# Additional Data

There are often scenarios where you need additional data for your navigation,
such as icons or images.  
The best approach is to create your own class (e.g. `MyCustomLink`) that extends
the `Link` class:

```php
use Feskol\Bundle\NavigationBundle\Navigation\Link\Link;

class MyCustomLink extends Link
{
    private ?string $icon = null;
    
    public function getIcon(): ?string {
        return $this->icon;
    }
    
    public function setIcon(?string $icon): static {
        $this->icon = $icon;
        return $this;
    }
}
```

Then use your `MyCustomLink` class instead of the `Link` class in your
navigation class, and set the additional data:

```php
$link = (new MyCustomLink())
    ->setTitle('Company')
    ->setRoute('app_company')
    ->setIcon('bi bi-user'); // For example, using Bootstrap-Icon classes
```
