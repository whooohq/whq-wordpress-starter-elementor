<div
	class="jet-mobile-menu__list"
	role="navigation"
>
	<ul class="jet-mobile-menu__items">
		<mobile-menu-item
			v-for="(item, index) in childrenObject"
			:key="item.id"
			:item-data-object="item"
			:depth="depth"
		></mobile-menu-item>
	</ul>
</div>
