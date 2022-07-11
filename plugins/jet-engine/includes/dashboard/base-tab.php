<?php

namespace Jet_Engine\Dashboard;

abstract class Base_Tab {

	abstract public function slug();

	abstract public function label();

	abstract public function load_config();

	public function condition() {
	    return true;
    }

	public function render_tab() {
		?>
        <cx-vui-tabs-panel
                name="<?= $this->slug() ?>"
                label="<?= $this->label() ?>"
                key="<?= $this->slug() ?>"
        >
            <keep-alive>
                <jet-engine-tab-<?= $this->slug() ?> />
            </keep-alive>
        </cx-vui-tabs-panel>
		<?php
	}

	public function render_assets() {
	}


}