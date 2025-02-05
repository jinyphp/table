<div class="offcanvas-lg offcanvas-start pe-lg-2 pe-xl-3 pe-xxl-4" id="filterSidebar">
    <div class="offcanvas-header border-bottom py-3">
        <h3 class="h5 offcanvas-title">Filters</h3>
        <button type="button" class="btn-close d-lg-none" data-bs-dismiss="offcanvas"
            data-bs-target="#filterSidebar" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-block">

        <!-- Location -->
        <div class="pb-4 mb-2 mb-xl-3">
            <h4 class="h6">Location and radius</h4>
            <div class="vstack gap-3">
                <div class="position-relative">
                    <i
                        class="fi-map-pin position-absolute top-50 start-0 translate-middle-y z-1 ms-3"></i>
                    <div class="choices" data-type="select-one" tabindex="0" role="combobox"
                        aria-autocomplete="list" aria-haspopup="true" aria-expanded="false">
                        <div class="form-select form-icon-start"><select
                                class="form-select form-icon-start choices__input"
                                data-select="{
        &quot;classNames&quot;: {
          &quot;containerInner&quot;: [&quot;form-select&quot;, &quot;form-icon-start&quot;]
        },
        &quot;searchEnabled&quot;: true
      }"
                                aria-label="Car location select" hidden="" tabindex="-1"
                                data-choice="active">
                                <option value="">Any location</option>
                                <option value="New York" selected="">New York</option>
                                <option value="Los Angeles">Los Angeles</option>
                                <option value="Chicago">Chicago</option>
                                <option value="Houston">Houston</option>
                                <option value="Phoenix">Phoenix</option>
                                <option value="Philadelphia">Philadelphia</option>
                                <option value="San Antonio">San Antonio</option>
                                <option value="San Diego">San Diego</option>
                                <option value="Dallas">Dallas</option>
                                <option value="San Jose">San Jose</option>
                            </select>
                            <div class="choices__list choices__list--single" role="listbox">
                                <div class="choices__item choices__item--selectable" data-item=""
                                    data-id="2" data-value="New York" aria-selected="true"
                                    role="option" data-deletable="">New York<button type="button"
                                        class="choices__button" aria-label="Remove item: New York"
                                        data-button="">Remove item</button></div>
                            </div>
                        </div>
                        <div class="choices__list choices__list--dropdown" aria-expanded="false"><input
                                type="search" class="choices__input choices__input--cloned"
                                autocomplete="off" autocapitalize="off" spellcheck="false"
                                role="textbox" aria-autocomplete="list" aria-label="Any location"
                                placeholder="Search...">
                            <div class="choices__list" role="listbox">
                                <div id="choices--ggzb-item-choice-1"
                                    class="choices__item choices__item--choice choices__placeholder choices__item--selectable is-highlighted"
                                    role="option" data-choice="" data-id="1" data-value=""
                                    data-choice-selectable="" aria-selected="true">Any location</div>
                                <div id="choices--ggzb-item-choice-2"
                                    class="choices__item choices__item--choice is-selected choices__item--selectable"
                                    role="option" data-choice="" data-id="2" data-value="New York"
                                    data-choice-selectable="">New York</div>
                                <div id="choices--ggzb-item-choice-3"
                                    class="choices__item choices__item--choice choices__item--selectable"
                                    role="option" data-choice="" data-id="3"
                                    data-value="Los Angeles" data-choice-selectable="">Los Angeles</div>
                                <div id="choices--ggzb-item-choice-4"
                                    class="choices__item choices__item--choice choices__item--selectable"
                                    role="option" data-choice="" data-id="4" data-value="Chicago"
                                    data-choice-selectable="">Chicago</div>
                                <div id="choices--ggzb-item-choice-5"
                                    class="choices__item choices__item--choice choices__item--selectable"
                                    role="option" data-choice="" data-id="5"
                                    data-value="Houston" data-choice-selectable="">Houston</div>
                                <div id="choices--ggzb-item-choice-6"
                                    class="choices__item choices__item--choice choices__item--selectable"
                                    role="option" data-choice="" data-id="6"
                                    data-value="Phoenix" data-choice-selectable="">Phoenix</div>
                                <div id="choices--ggzb-item-choice-7"
                                    class="choices__item choices__item--choice choices__item--selectable"
                                    role="option" data-choice="" data-id="7"
                                    data-value="Philadelphia" data-choice-selectable="">Philadelphia
                                </div>
                                <div id="choices--ggzb-item-choice-8"
                                    class="choices__item choices__item--choice choices__item--selectable"
                                    role="option" data-choice="" data-id="8"
                                    data-value="San Antonio" data-choice-selectable="">San Antonio
                                </div>
                                <div id="choices--ggzb-item-choice-9"
                                    class="choices__item choices__item--choice choices__item--selectable"
                                    role="option" data-choice="" data-id="9"
                                    data-value="San Diego" data-choice-selectable="">San Diego</div>
                                <div id="choices--ggzb-item-choice-10"
                                    class="choices__item choices__item--choice choices__item--selectable"
                                    role="option" data-choice="" data-id="10" data-value="Dallas"
                                    data-choice-selectable="">Dallas</div>
                                <div id="choices--ggzb-item-choice-11"
                                    class="choices__item choices__item--choice choices__item--selectable"
                                    role="option" data-choice="" data-id="11"
                                    data-value="San Jose" data-choice-selectable="">San Jose</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="position-relative">
                    <i
                        class="fi-navigation position-absolute top-50 start-0 translate-middle-y z-1 ms-3"></i>
                    <div class="choices" data-type="select-one" tabindex="0" role="listbox"
                        aria-haspopup="true" aria-expanded="false">
                        <div class="form-select form-icon-start"><select
                                class="form-select form-icon-start choices__input"
                                data-select="{
        &quot;classNames&quot;: {
          &quot;containerInner&quot;: [&quot;form-select&quot;, &quot;form-icon-start&quot;]
        }
      }"
                                aria-label="Location radius select" hidden="" tabindex="-1"
                                data-choice="active">
                                <option value="">Any radius</option>
                                <option value="10 mi">10 mi</option>
                                <option value="20 mi">20 mi</option>
                                <option value="30 mi">30 mi</option>
                                <option value="40 mi">40 mi</option>
                                <option value="50 mi" selected="">50 mi</option>
                                <option value="60 mi">60 mi</option>
                            </select>
                            <div class="choices__list choices__list--single">
                                <div class="choices__item choices__item--selectable" data-item=""
                                    data-id="6" data-value="50 mi" aria-selected="true"
                                    role="option" data-deletable="">50 mi<button type="button"
                                        class="choices__button" aria-label="Remove item: 50 mi"
                                        data-button="">Remove item</button></div>
                            </div>
                        </div>
                        <div class="choices__list choices__list--dropdown" aria-expanded="false">
                            <div class="choices__list" role="listbox">
                                <div id="choices--5hfh-item-choice-1"
                                    class="choices__item choices__item--choice choices__placeholder choices__item--selectable is-highlighted"
                                    role="option" data-choice="" data-id="1" data-value=""
                                    data-choice-selectable="" aria-selected="true">Any radius</div>
                                <div id="choices--5hfh-item-choice-2"
                                    class="choices__item choices__item--choice choices__item--selectable"
                                    role="option" data-choice="" data-id="2" data-value="10 mi"
                                    data-choice-selectable="">10 mi</div>
                                <div id="choices--5hfh-item-choice-3"
                                    class="choices__item choices__item--choice choices__item--selectable"
                                    role="option" data-choice="" data-id="3" data-value="20 mi"
                                    data-choice-selectable="">20 mi</div>
                                <div id="choices--5hfh-item-choice-4"
                                    class="choices__item choices__item--choice choices__item--selectable"
                                    role="option" data-choice="" data-id="4" data-value="30 mi"
                                    data-choice-selectable="">30 mi</div>
                                <div id="choices--5hfh-item-choice-5"
                                    class="choices__item choices__item--choice choices__item--selectable"
                                    role="option" data-choice="" data-id="5" data-value="40 mi"
                                    data-choice-selectable="">40 mi</div>
                                <div id="choices--5hfh-item-choice-6"
                                    class="choices__item choices__item--choice is-selected choices__item--selectable"
                                    role="option" data-choice="" data-id="6" data-value="50 mi"
                                    data-choice-selectable="">50 mi</div>
                                <div id="choices--5hfh-item-choice-7"
                                    class="choices__item choices__item--choice choices__item--selectable"
                                    role="option" data-choice="" data-id="7" data-value="60 mi"
                                    data-choice-selectable="">60 mi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project type -->
        <div class="pb-4 mb-2 mb-xl-3">
            <h4 class="h6">Project type</h4>
            <div
                data-filter-list="{&quot;searchClass&quot;: &quot;project-search&quot;, &quot;listClass&quot;: &quot;project-list&quot;, &quot;valueNames&quot;: [&quot;form-check-label&quot;]}">
                <div class="position-relative mb-3">
                    <i class="fi-search position-absolute top-50 start-0 translate-middle-y ms-3"></i>
                    <input type="search" class="project-search form-control form-icon-start"
                        placeholder="Search">
                    <button
                        class="btn btn-sm btn-outline-secondary w-auto border-0 p-1 position-absolute top-50 end-0 translate-middle-y me-2 opacity-0">
                        <svg class="opacity-75" width="16" height="16" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M18.619 5.381a.875.875 0 0 1 0 1.238l-12 12A.875.875 0 0 1 5.38 17.38l12-12a.875.875 0 0 1 1.238 0Z">
                            </path>
                            <path
                                d="M5.381 5.381a.875.875 0 0 1 1.238 0l12 12a.875.875 0 1 1-1.238 1.238l-12-12a.875.875 0 0 1 0-1.238Z">
                            </path>
                        </svg>
                    </button>
                </div>
                <div style="height: 215px" data-simplebar="init" data-simplebar-auto-hide="false"
                    class="simplebar-scrollable-y">
                    <div class="simplebar-wrapper" style="margin: 0px;">
                        <div class="simplebar-height-auto-observer-wrapper">
                            <div class="simplebar-height-auto-observer"></div>
                        </div>
                        <div class="simplebar-mask">
                            <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                    aria-label="scrollable content"
                                    style="height: 100%; overflow: hidden scroll;">
                                    <div class="simplebar-content" style="padding: 0px;">
                                        <div class="project-list d-flex flex-column gap-2">
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="appliance-installation" checked="">
                                                <label for="appliance-installation"
                                                    class="form-check-label">
                                                    Appliance installation
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="bathtub-repair">
                                                <label for="bathtub-repair" class="form-check-label">
                                                    Bathtub repair
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="door-repair">
                                                <label for="door-repair" class="form-check-label">
                                                    Door repair
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="heating" checked="">
                                                <label for="heating" class="form-check-label">
                                                    Heating &amp; furnace
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="locksmith">
                                                <label for="locksmith" class="form-check-label">
                                                    Locksmith
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="small-appliance-repair">
                                                <label for="small-appliance-repair"
                                                    class="form-check-label">
                                                    Small appliance repair
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="smoke-detector-installation">
                                                <label for="smoke-detector-installation"
                                                    class="form-check-label">
                                                    Smoke detector installation
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="electrical-work">
                                                <label for="electrical-work" class="form-check-label">
                                                    Electrical work
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="plumbing">
                                                <label for="plumbing" class="form-check-label">
                                                    Plumbing
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="hvac-maintenance">
                                                <label for="hvac-maintenance"
                                                    class="form-check-label">
                                                    HVAC maintenance
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="painting">
                                                <label for="painting" class="form-check-label">
                                                    Painting
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="roofing" checked="">
                                                <label for="roofing" class="form-check-label">
                                                    Roofing
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="flooring-installation">
                                                <label for="flooring-installation"
                                                    class="form-check-label">
                                                    Flooring installation
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="carpentry" checked="">
                                                <label for="carpentry" class="form-check-label">
                                                    Carpentry
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="landscaping">
                                                <label for="landscaping" class="form-check-label">
                                                    Landscaping
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="window-installation">
                                                <label for="window-installation"
                                                    class="form-check-label">
                                                    Window installation
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="home-security-systems">
                                                <label for="home-security-systems"
                                                    class="form-check-label">
                                                    Home security systems
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="drywall-repair">
                                                <label for="drywall-repair" class="form-check-label">
                                                    Drywall repair
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="gutter-cleaning">
                                                <label for="gutter-cleaning" class="form-check-label">
                                                    Gutter cleaning
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="insulation-installation">
                                                <label for="insulation-installation"
                                                    class="form-check-label">
                                                    Insulation installation
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="kitchen-remodeling">
                                                <label for="kitchen-remodeling"
                                                    class="form-check-label">
                                                    Kitchen remodeling
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="bathroom-remodeling">
                                                <label for="bathroom-remodeling"
                                                    class="form-check-label">
                                                    Bathroom remodeling
                                                </label>
                                            </div>
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input"
                                                    id="pest-control">
                                                <label for="pest-control" class="form-check-label">
                                                    Pest control
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="simplebar-placeholder" style="width: 272px; height: 728px;"></div>
                    </div>
                    <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                        <div class="simplebar-scrollbar simplebar-visible"
                            style="width: 0px; display: none;"></div>
                    </div>
                    <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                        <div class="simplebar-scrollbar simplebar-visible"
                            style="height: 63px; transform: translate3d(0px, 0px, 0px); display: block;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget -->
        <div class="pb-4 mb-2 mb-xl-3">
            <h4 class="h6">Budget</h4>
            <div class="d-flex flex-column gap-2">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="budget-4">
                    <label class="form-check-label fs-sm" for="budget-4">$$$$</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="budget-3">
                    <label class="form-check-label fs-sm" for="budget-3">$$$</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="budget-2" checked="">
                    <label class="form-check-label fs-sm" for="budget-2">$$</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="budget-1">
                    <label class="form-check-label fs-sm" for="budget-1">$</label>
                </div>
            </div>
        </div>

        <!-- Features -->
        <div class="pb-4 mb-2 mb-xl-3">
            <h4 class="h6">Features</h4>
            <div class="d-flex flex-column gap-2">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="eco-friendly"
                        checked="">
                    <label class="form-check-label fs-sm" for="eco-friendly">Eco-friendly</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="free-consultation">
                    <label class="form-check-label fs-sm" for="free-consultation">Free
                        consultation</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="online-consultation">
                    <label class="form-check-label fs-sm" for="online-consultation">Online
                        consultation</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="free-estimate">
                    <label class="form-check-label fs-sm" for="free-estimate">Free estimate</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="verified-hires"
                        checked="">
                    <label class="form-check-label fs-sm" for="verified-hires">Verified hires</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="weekend-consultations">
                    <label class="form-check-label fs-sm" for="weekend-consultations">Weekend
                        consultations</label>
                </div>
            </div>
        </div>

        <!-- Availability -->
        <div class="pb-4 mb-2 mb-xl-3">
            <h4 class="h6">Availability</h4>
            <div class="d-flex flex-column gap-2">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="now">
                    <label class="form-check-label fs-sm" for="now">Available now</label>
                </div>
            </div>
        </div>

        <!-- Average rating -->
        <div class="pb-2 pb-lg-0">
            <h4 class="h6">Average rating</h4>
            <div class="d-flex flex-column gap-2">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="star-5" checked="">
                    <label class="form-check-label d-flex align-items-center fs-sm" for="star-5">
                        5 <i class="fi-star-filled text-warning ms-1"></i>
                    </label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="star-4" checked="">
                    <label class="form-check-label d-flex align-items-center fs-sm" for="star-4">
                        4 <i class="fi-star-filled text-warning ms-1"></i>
                    </label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="star-3">
                    <label class="form-check-label d-flex align-items-center fs-sm" for="star-3">
                        3 <i class="fi-star-filled text-warning ms-1"></i>
                    </label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="star-2">
                    <label class="form-check-label d-flex align-items-center fs-sm" for="star-2">
                        2-1 <i class="fi-star-filled text-warning ms-1"></i>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
