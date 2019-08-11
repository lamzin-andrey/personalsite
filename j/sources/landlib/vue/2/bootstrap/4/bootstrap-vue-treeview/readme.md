# En

## About 
This is the fork https://github.com/kamil-lip/bootstrap-vue-treeview

Unfortunately, the original did not possess all the properties I needed. In addition, it contained a bug when deleting nested elements - when trying to delete the first or second, the last one was always deleted.

## New events

Append two events `nodeRenamed` and `deleteNodeEx`.

### nodeRenamed

Emitted, when GUI node was renamed. Application, uses this component can send request on the server and update it data.

### deleteNodeEx

Emitted, when was select context menu point "Delete node" and node delete started. Event listener got three arguments:

#### node

Object. TreeNode, which was deleted from GUI tree.

#### nodeData

Array of nodes data. Array of deleted node and all him childs from all levels.
For example, application can send data for removing  to the server and if server not avialable, application can restore all nodes in the tree view.

#### idList

Array of key values of the deleted node and all him childs from all levels. For example application can send it list on the server for remove all his child and target node from the database.

# Ru

## Что это

Форк классного фронтенд дерева https://github.com/kamil-lip/bootstrap-vue-treeview

К сожалению, оригинал не обладал всеми необходимыми мне свойствами. К тому же он содержал баг при удалении вложенных элементов - при попытке удаления первого или второго всегда удалялся последний.

## New events

Добавлены два события `nodeRenamed` и `deleteNodeEx`.

### nodeRenamed

Происходит, когда переименование элемента дерева закончено. это позволяет приложению, использующему компонент отправить запрос на сервер.

### deleteNodeEx

Происходит, когда начато стандартное удаление узла дерева. Обработчик события принимает три аргумента.

#### node

Объект. Узел, который был удалён.

#### nodeData

Массив удаленного узла. Данные удаленного узла и всех вложенных в удалённый узел узлов (это позволяет восстановить ветку дерева, например если сервер оказался недоступен).

#### idList

Массив целых чисел. Список идентификаторов удаленного узла и всех вложенных в него (это позволяет легко отправить эти данные)