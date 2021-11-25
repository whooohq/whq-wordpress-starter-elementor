<div
	:class="instanceClass"
	v-on:keyup.esc="escapeKeyHandler"
>
	<div
		class="jet-mobile-menu__toggle"
		ref="toggle"
		tabindex="1"
		aria-label="Open/Close Menu"
		v-on:click="menuToggle"
		v-on:keyup.enter="menuToggle"
	>
		<div
			class="jet-mobile-menu__template-loader"
			v-if="toggleLoaderVisible"
		>
			<svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="24px" height="25px" viewBox="0 0 128 128" xml:space="preserve">
				<g>
					<linearGradient id="linear-gradient">
						<stop offset="0%" :stop-color="loaderColor" stop-opacity="0"/>
						<stop offset="100%" :stop-color="loaderColor" stop-opacity="1"/>
					</linearGradient>
				<path d="M63.85 0A63.85 63.85 0 1 1 0 63.85 63.85 63.85 0 0 1 63.85 0zm.65 19.5a44 44 0 1 1-44 44 44 44 0 0 1 44-44z" fill="url(#linear-gradient)" fill-rule="evenodd"/>
				<animateTransform attributeName="transform" type="rotate" from="0 64 64" to="360 64 64" dur="1080ms" repeatCount="indefinite"></animateTransform>
				</g>
			</svg>
		</div>

		<div
			class="jet-mobile-menu__toggle-icon"
			v-if="!menuOpen && !toggleLoaderVisible"
			v-html="toggleClosedIcon"
		></div>
		<div
			class="jet-mobile-menu__toggle-icon"
			v-if="menuOpen && !toggleLoaderVisible"
			v-html="toggleOpenedIcon"
		></div>
		<span
			class="jet-mobile-menu__toggle-text"
			v-if="toggleText"
			v-html="toggleText"
		></span>

	</div>

	<transition name="cover-animation">
		<div
			class="jet-mobile-menu-cover"
			v-if="menuContainerVisible && coverVisible"
			v-on:click="closeMenu"
		></div>
	</transition>

	<transition :name="showAnimation">
		<div
			class="jet-mobile-menu__container"
			v-if="menuContainerVisible"
		>
			<div
				class="jet-mobile-menu__container-inner"
			>
				<div
					class="jet-mobile-menu__header-template"
					v-if="headerTemplateVisible"
				>
					<div
						class="jet-mobile-menu__header-template-content"
						ref="header-template-content"
						v-html="headerContent"
					></div>
				</div>

				<div
					class="jet-mobile-menu__controls"
				>
					<div
						class="jet-mobile-menu__breadcrumbs"
						v-if="isBreadcrumbs"
					>
						<div
							class="jet-mobile-menu__breadcrumb"
							v-for="(item, index) in breadcrumbsPathData"
							:key="index"
						>
							<div
								class="breadcrumb-label"
								v-on:click="breadcrumbHandle(index+1)"
								v-html="item"
							></div>
							<div
								class="breadcrumb-divider"
								v-html="breadcrumbIcon"
								v-if="(breadcrumbIcon && index !== breadcrumbsPathData.length-1)"
							></div>
						</div>
					</div>
					<div
						class="jet-mobile-menu__back"
						ref="back"
						tabindex="1"
						aria-label="Close Menu"
						v-if="!isBack && isClose"
						v-html="closeIcon"
						v-on:click="menuToggle"
						v-on:keyup.enter="menuToggle"
					></div>
					<div
						class="jet-mobile-menu__back"
						ref="back"
						tabindex="1"
						aria-label="Back to Prev Items"
						v-if="isBack"
						v-html="backIcon"
						v-on:click="goBack"
						v-on:keyup.enter="goBack"
					></div>
				</div>

				<div
					class="jet-mobile-menu__before-template"
					v-if="beforeTemplateVisible"
				>
					<div
						class="jet-mobile-menu__before-template-content"
						ref="before-template-content"
						v-html="beforeContent"
					></div>
				</div>

				<div
					class="jet-mobile-menu__body"
				>
					<transition :name="animation">
						<mobile-menu-list
							v-if="!templateVisible"
							:key="depth"
							:depth="depth"
							:children-object="itemsList"
						></mobile-menu-list>
						<div
							class="jet-mobile-menu__template"
							ref="template-content"
							v-if="templateVisible"
						>
							<div
								class="jet-mobile-menu__template-content"
								v-html="itemTemplateContent"
							></div>
						</div>
					</transition>
				</div>

				<div
					class="jet-mobile-menu__after-template"
					v-if="afterTemplateVisible"
				>
					<div
						class="jet-mobile-menu__after-template-content"
						ref="after-template-content"
						v-html="afterContent"
					></div>
				</div>

			</div>
		</div>
	</transition>
</div>
