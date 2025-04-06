<!DOCTYPE html>
<html lang="pl" dir="ltr" data-beasties-container>

<head>
    @include('layouts.header')
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <title>Dashboard</title>
    <base href="/">
    <style>
        @font-face {
            font-family: 'Material Icons';
            font-style: normal;
            font-weight: 400;
            src: url(https://fonts.gstatic.com/s/materialicons/v143/flUhRq6tzZclQEJ-Vdg-IuiaDsNc.woff2) format('woff2');
        }

        .material-icons {
            font-family: 'Material Icons';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }
    </style>
    <style>
        html {
            --mat-sys-background: #fff8f8;
            --mat-sys-error: #ba1a1a;
            --mat-sys-error-container: #ffdad6;
            --mat-sys-inverse-on-surface: #faeeef;
            --mat-sys-inverse-primary: #ffb1c5;
            --mat-sys-inverse-surface: #352f30;
            --mat-sys-on-background: #201a1b;
            --mat-sys-on-error: #ffffff;
            --mat-sys-on-error-container: #93000a;
            --mat-sys-on-primary: #ffffff;
            --mat-sys-on-primary-container: #8f0045;
            --mat-sys-on-primary-fixed: #3f001b;
            --mat-sys-on-primary-fixed-variant: #8f0045;
            --mat-sys-on-secondary: #ffffff;
            --mat-sys-on-secondary-container: #5b3f46;
            --mat-sys-on-secondary-fixed: #2b151b;
            --mat-sys-on-secondary-fixed-variant: #5b3f46;
            --mat-sys-on-surface: #201a1b;
            --mat-sys-on-surface-variant: #514346;
            --mat-sys-on-tertiary: #ffffff;
            --mat-sys-on-tertiary-container: #930100;
            --mat-sys-on-tertiary-fixed: #410000;
            --mat-sys-on-tertiary-fixed-variant: #930100;
            --mat-sys-outline: #847376;
            --mat-sys-outline-variant: #d6c2c5;
            --mat-sys-primary: #ba005c;
            --mat-sys-primary-container: #ffd9e1;
            --mat-sys-primary-fixed: #ffd9e1;
            --mat-sys-primary-fixed-dim: #ffb1c5;
            --mat-sys-scrim: #000000;
            --mat-sys-secondary: #74565d;
            --mat-sys-secondary-container: #ffd9e1;
            --mat-sys-secondary-fixed: #ffd9e1;
            --mat-sys-secondary-fixed-dim: #e3bdc5;
            --mat-sys-shadow: #000000;
            --mat-sys-surface: #fff8f8;
            --mat-sys-surface-bright: #fff8f8;
            --mat-sys-surface-container: #f7ebec;
            --mat-sys-surface-container-high: #f1e5e6;
            --mat-sys-surface-container-highest: #ece0e1;
            --mat-sys-surface-container-low: #fdf1f2;
            --mat-sys-surface-container-lowest: #ffffff;
            --mat-sys-surface-dim: #e3d7d8;
            --mat-sys-surface-tint: #ba005c;
            --mat-sys-surface-variant: #f3dde1;
            --mat-sys-tertiary: #c00100;
            --mat-sys-tertiary-container: #ffdad4;
            --mat-sys-tertiary-fixed: #ffdad4;
            --mat-sys-tertiary-fixed-dim: #ffb4a8;
            --mat-sys-neutral-variant20: #3a2d30;
            --mat-sys-neutral10: #201a1b
        }

        html {
            --mat-sys-body-large: 400 1rem / 1.5rem Roboto;
            --mat-sys-body-large-font: Roboto;
            --mat-sys-body-large-line-height: 1.5rem;
            --mat-sys-body-large-size: 1rem;
            --mat-sys-body-large-tracking: .031rem;
            --mat-sys-body-large-weight: 400;
            --mat-sys-body-medium: 400 .875rem / 1.25rem Roboto;
            --mat-sys-body-medium-font: Roboto;
            --mat-sys-body-medium-line-height: 1.25rem;
            --mat-sys-body-medium-size: .875rem;
            --mat-sys-body-medium-tracking: .016rem;
            --mat-sys-body-medium-weight: 400;
            --mat-sys-body-small: 400 .75rem / 1rem Roboto;
            --mat-sys-body-small-font: Roboto;
            --mat-sys-body-small-line-height: 1rem;
            --mat-sys-body-small-size: .75rem;
            --mat-sys-body-small-tracking: .025rem;
            --mat-sys-body-small-weight: 400;
            --mat-sys-display-large: 400 3.562rem / 4rem Roboto;
            --mat-sys-display-large-font: Roboto;
            --mat-sys-display-large-line-height: 4rem;
            --mat-sys-display-large-size: 3.562rem;
            --mat-sys-display-large-tracking: -.016rem;
            --mat-sys-display-large-weight: 400;
            --mat-sys-display-medium: 400 2.812rem / 3.25rem Roboto;
            --mat-sys-display-medium-font: Roboto;
            --mat-sys-display-medium-line-height: 3.25rem;
            --mat-sys-display-medium-size: 2.812rem;
            --mat-sys-display-medium-tracking: 0;
            --mat-sys-display-medium-weight: 400;
            --mat-sys-display-small: 400 2.25rem / 2.75rem Roboto;
            --mat-sys-display-small-font: Roboto;
            --mat-sys-display-small-line-height: 2.75rem;
            --mat-sys-display-small-size: 2.25rem;
            --mat-sys-display-small-tracking: 0;
            --mat-sys-display-small-weight: 400;
            --mat-sys-headline-large: 400 2rem / 2.5rem Roboto;
            --mat-sys-headline-large-font: Roboto;
            --mat-sys-headline-large-line-height: 2.5rem;
            --mat-sys-headline-large-size: 2rem;
            --mat-sys-headline-large-tracking: 0;
            --mat-sys-headline-large-weight: 400;
            --mat-sys-headline-medium: 400 1.75rem / 2.25rem Roboto;
            --mat-sys-headline-medium-font: Roboto;
            --mat-sys-headline-medium-line-height: 2.25rem;
            --mat-sys-headline-medium-size: 1.75rem;
            --mat-sys-headline-medium-tracking: 0;
            --mat-sys-headline-medium-weight: 400;
            --mat-sys-headline-small: 400 1.5rem / 2rem Roboto;
            --mat-sys-headline-small-font: Roboto;
            --mat-sys-headline-small-line-height: 2rem;
            --mat-sys-headline-small-size: 1.5rem;
            --mat-sys-headline-small-tracking: 0;
            --mat-sys-headline-small-weight: 400;
            --mat-sys-label-large: 500 .875rem / 1.25rem Roboto;
            --mat-sys-label-large-font: Roboto;
            --mat-sys-label-large-line-height: 1.25rem;
            --mat-sys-label-large-size: .875rem;
            --mat-sys-label-large-tracking: .006rem;
            --mat-sys-label-large-weight: 500;
            --mat-sys-label-large-weight-prominent: 700;
            --mat-sys-label-medium: 500 .75rem / 1rem Roboto;
            --mat-sys-label-medium-font: Roboto;
            --mat-sys-label-medium-line-height: 1rem;
            --mat-sys-label-medium-size: .75rem;
            --mat-sys-label-medium-tracking: .031rem;
            --mat-sys-label-medium-weight: 500;
            --mat-sys-label-medium-weight-prominent: 700;
            --mat-sys-label-small: 500 .688rem / 1rem Roboto;
            --mat-sys-label-small-font: Roboto;
            --mat-sys-label-small-line-height: 1rem;
            --mat-sys-label-small-size: .688rem;
            --mat-sys-label-small-tracking: .031rem;
            --mat-sys-label-small-weight: 500;
            --mat-sys-title-large: 400 1.375rem / 1.75rem Roboto;
            --mat-sys-title-large-font: Roboto;
            --mat-sys-title-large-line-height: 1.75rem;
            --mat-sys-title-large-size: 1.375rem;
            --mat-sys-title-large-tracking: 0;
            --mat-sys-title-large-weight: 400;
            --mat-sys-title-medium: 500 1rem / 1.5rem Roboto;
            --mat-sys-title-medium-font: Roboto;
            --mat-sys-title-medium-line-height: 1.5rem;
            --mat-sys-title-medium-size: 1rem;
            --mat-sys-title-medium-tracking: .009rem;
            --mat-sys-title-medium-weight: 500;
            --mat-sys-title-small: 500 .875rem / 1.25rem Roboto;
            --mat-sys-title-small-font: Roboto;
            --mat-sys-title-small-line-height: 1.25rem;
            --mat-sys-title-small-size: .875rem;
            --mat-sys-title-small-tracking: .006rem;
            --mat-sys-title-small-weight: 500
        }

        :root {
            --mat-sys-primary: #705c33;
            --mat-sys-on-primary: #ffffff;
            --mat-sys-primary-container: #fbdfab;
            --mat-sys-on-primary-container: #261900;
            --mat-sys-inverse-primary: #dec391;
            --mat-sys-primary-fixed: #fbdfab;
            --mat-sys-primary-fixed-dim: #dec391;
            --mat-sys-on-primary-fixed: #261900;
            --mat-sys-on-primary-fixed-variant: #56441d;
            --mat-sys-secondary: #665d4d;
            --mat-sys-on-secondary: #ffffff;
            --mat-sys-secondary-container: #eee1cc;
            --mat-sys-on-secondary-container: #211b0e;
            --mat-sys-secondary-fixed: #eee1cc;
            --mat-sys-secondary-fixed-dim: #d1c5b0;
            --mat-sys-on-secondary-fixed: #211b0e;
            --mat-sys-on-secondary-fixed-variant: #4e4636;
            --mat-sys-tertiary: #655e4f;
            --mat-sys-on-tertiary: #ffffff;
            --mat-sys-tertiary-container: #ece1cf;
            --mat-sys-on-tertiary-container: #201b10;
            --mat-sys-tertiary-fixed: #ece1cf;
            --mat-sys-tertiary-fixed-dim: #d0c5b4;
            --mat-sys-on-tertiary-fixed: #201b10;
            --mat-sys-on-tertiary-fixed-variant: #4d4639;
            --mat-sys-background: #fdf8f6;
            --mat-sys-on-background: #1c1b1a;
            --mat-sys-surface: #fdf8f6;
            --mat-sys-surface-dim: #ded9d7;
            --mat-sys-surface-bright: #fdf8f6;
            --mat-sys-surface-container-lowest: #ffffff;
            --mat-sys-surface-container-low: #f8f3f0;
            --mat-sys-surface-container: #f2edea;
            --mat-sys-surface-container-high: #ece7e5;
            --mat-sys-surface-container-highest: #e6e2df;
            --mat-sys-on-surface: #1c1b1a;
            --mat-sys-shadow: #000000;
            --mat-sys-scrim: #000000;
            --mat-sys-surface-tint: #655e4f;
            --mat-sys-inverse-surface: #32302f;
            --mat-sys-inverse-on-surface: #f5f0ed;
            --mat-sys-outline: #7c766d;
            --mat-sys-outline-variant: #cdc5ba;
            --mat-sys-error: #ba1a1a;
            --mat-sys-error-container: #ffdad6;
            --mat-sys-on-error: #ffffff;
            --mat-sys-on-error-container: #410002;
            --mat-sys-surface-variant: #eae1d6;
            --mat-sys-on-surface-variant: #4b463e
        }

        @font-face {
            font-family: Raleway;
            src: url("./media/Raleway-VariableFont_wght-TCZQGHRG.ttf") format("truetype");
            font-weight: 300 400 600;
            font-style: normal
        }

        @font-face {
            font-family: Cinzel;
            src: url("./media/Cinzel-VariableFont_wght-T3KVP44J.ttf") format("truetype");
            font-weight: 400 700;
            font-style: normal
        }

        html {
            --mat-sys-background: #fef8fc;
            --mat-sys-error: #ba1a1a;
            --mat-sys-error-container: #ffdad6;
            --mat-sys-inverse-on-surface: #f5eff4;
            --mat-sys-inverse-primary: #d5baff;
            --mat-sys-inverse-surface: #323033;
            --mat-sys-on-background: #1d1b1e;
            --mat-sys-on-error: #ffffff;
            --mat-sys-on-error-container: #93000a;
            --mat-sys-on-primary: #ffffff;
            --mat-sys-on-primary-container: #5f00c0;
            --mat-sys-on-primary-fixed: #270057;
            --mat-sys-on-primary-fixed-variant: #5f00c0;
            --mat-sys-on-secondary: #ffffff;
            --mat-sys-on-secondary-container: #4b4357;
            --mat-sys-on-secondary-fixed: #1f182a;
            --mat-sys-on-secondary-fixed-variant: #4b4357;
            --mat-sys-on-surface: #1d1b1e;
            --mat-sys-on-surface-variant: #49454e;
            --mat-sys-on-tertiary: #ffffff;
            --mat-sys-on-tertiary-container: #5f00c0;
            --mat-sys-on-tertiary-fixed: #270057;
            --mat-sys-on-tertiary-fixed-variant: #5f00c0;
            --mat-sys-outline: #7b757f;
            --mat-sys-outline-variant: #cbc4cf;
            --mat-sys-primary: #7d00fa;
            --mat-sys-primary-container: #ecdcff;
            --mat-sys-primary-fixed: #ecdcff;
            --mat-sys-primary-fixed-dim: #d5baff;
            --mat-sys-scrim: #000000;
            --mat-sys-secondary: #645b70;
            --mat-sys-secondary-container: #eadef7;
            --mat-sys-secondary-fixed: #eadef7;
            --mat-sys-secondary-fixed-dim: #cec2db;
            --mat-sys-shadow: #000000;
            --mat-sys-surface: #fef8fc;
            --mat-sys-surface-bright: #fef8fc;
            --mat-sys-surface-container: #f2ecf1;
            --mat-sys-surface-container-high: #ede6eb;
            --mat-sys-surface-container-highest: #e6e1e6;
            --mat-sys-surface-container-low: #f8f2f6;
            --mat-sys-surface-container-lowest: #ffffff;
            --mat-sys-surface-dim: #ded8dd;
            --mat-sys-surface-tint: #7d00fa;
            --mat-sys-surface-variant: #e8e0eb;
            --mat-sys-tertiary: #7d00fa;
            --mat-sys-tertiary-container: #ecdcff;
            --mat-sys-tertiary-fixed: #ecdcff;
            --mat-sys-tertiary-fixed-dim: #d5baff;
            --mat-sys-neutral-variant20: #332f37;
            --mat-sys-neutral10: #1d1b1e
        }

        html {
            --mat-sys-level0: 0px 0px 0px 0px rgba(0, 0, 0, .2), 0px 0px 0px 0px rgba(0, 0, 0, .14), 0px 0px 0px 0px rgba(0, 0, 0, .12)
        }

        html {
            --mat-sys-level1: 0px 2px 1px -1px rgba(0, 0, 0, .2), 0px 1px 1px 0px rgba(0, 0, 0, .14), 0px 1px 3px 0px rgba(0, 0, 0, .12)
        }

        html {
            --mat-sys-level2: 0px 3px 3px -2px rgba(0, 0, 0, .2), 0px 3px 4px 0px rgba(0, 0, 0, .14), 0px 1px 8px 0px rgba(0, 0, 0, .12)
        }

        html {
            --mat-sys-level3: 0px 3px 5px -1px rgba(0, 0, 0, .2), 0px 6px 10px 0px rgba(0, 0, 0, .14), 0px 1px 18px 0px rgba(0, 0, 0, .12)
        }

        html {
            --mat-sys-level4: 0px 5px 5px -3px rgba(0, 0, 0, .2), 0px 8px 10px 1px rgba(0, 0, 0, .14), 0px 3px 14px 2px rgba(0, 0, 0, .12)
        }

        html {
            --mat-sys-level5: 0px 7px 8px -4px rgba(0, 0, 0, .2), 0px 12px 17px 2px rgba(0, 0, 0, .14), 0px 5px 22px 4px rgba(0, 0, 0, .12)
        }

        html {
            --mat-sys-body-large: 400 1rem / 1.5rem Raleway;
            --mat-sys-body-large-font: Raleway;
            --mat-sys-body-large-line-height: 1.5rem;
            --mat-sys-body-large-size: 1rem;
            --mat-sys-body-large-tracking: .031rem;
            --mat-sys-body-large-weight: 400;
            --mat-sys-body-medium: 400 .875rem / 1.25rem Raleway;
            --mat-sys-body-medium-font: Raleway;
            --mat-sys-body-medium-line-height: 1.25rem;
            --mat-sys-body-medium-size: .875rem;
            --mat-sys-body-medium-tracking: .016rem;
            --mat-sys-body-medium-weight: 400;
            --mat-sys-body-small: 400 .75rem / 1rem Raleway;
            --mat-sys-body-small-font: Raleway;
            --mat-sys-body-small-line-height: 1rem;
            --mat-sys-body-small-size: .75rem;
            --mat-sys-body-small-tracking: .025rem;
            --mat-sys-body-small-weight: 400;
            --mat-sys-display-large: 400 3.562rem / 4rem Cinzel;
            --mat-sys-display-large-font: Cinzel;
            --mat-sys-display-large-line-height: 4rem;
            --mat-sys-display-large-size: 3.562rem;
            --mat-sys-display-large-tracking: -.016rem;
            --mat-sys-display-large-weight: 400;
            --mat-sys-display-medium: 400 2.812rem / 3.25rem Cinzel;
            --mat-sys-display-medium-font: Cinzel;
            --mat-sys-display-medium-line-height: 3.25rem;
            --mat-sys-display-medium-size: 2.812rem;
            --mat-sys-display-medium-tracking: 0;
            --mat-sys-display-medium-weight: 400;
            --mat-sys-display-small: 400 2.25rem / 2.75rem Cinzel;
            --mat-sys-display-small-font: Cinzel;
            --mat-sys-display-small-line-height: 2.75rem;
            --mat-sys-display-small-size: 2.25rem;
            --mat-sys-display-small-tracking: 0;
            --mat-sys-display-small-weight: 400;
            --mat-sys-headline-large: 400 2rem / 2.5rem Cinzel;
            --mat-sys-headline-large-font: Cinzel;
            --mat-sys-headline-large-line-height: 2.5rem;
            --mat-sys-headline-large-size: 2rem;
            --mat-sys-headline-large-tracking: 0;
            --mat-sys-headline-large-weight: 400;
            --mat-sys-headline-medium: 400 1.75rem / 2.25rem Cinzel;
            --mat-sys-headline-medium-font: Cinzel;
            --mat-sys-headline-medium-line-height: 2.25rem;
            --mat-sys-headline-medium-size: 1.75rem;
            --mat-sys-headline-medium-tracking: 0;
            --mat-sys-headline-medium-weight: 400;
            --mat-sys-headline-small: 400 1.5rem / 2rem Cinzel;
            --mat-sys-headline-small-font: Cinzel;
            --mat-sys-headline-small-line-height: 2rem;
            --mat-sys-headline-small-size: 1.5rem;
            --mat-sys-headline-small-tracking: 0;
            --mat-sys-headline-small-weight: 400;
            --mat-sys-label-large: 500 .875rem / 1.25rem Raleway;
            --mat-sys-label-large-font: Raleway;
            --mat-sys-label-large-line-height: 1.25rem;
            --mat-sys-label-large-size: .875rem;
            --mat-sys-label-large-tracking: .006rem;
            --mat-sys-label-large-weight: 500;
            --mat-sys-label-large-weight-prominent: 700;
            --mat-sys-label-medium: 500 .75rem / 1rem Raleway;
            --mat-sys-label-medium-font: Raleway;
            --mat-sys-label-medium-line-height: 1rem;
            --mat-sys-label-medium-size: .75rem;
            --mat-sys-label-medium-tracking: .031rem;
            --mat-sys-label-medium-weight: 500;
            --mat-sys-label-medium-weight-prominent: 700;
            --mat-sys-label-small: 500 .688rem / 1rem Raleway;
            --mat-sys-label-small-font: Raleway;
            --mat-sys-label-small-line-height: 1rem;
            --mat-sys-label-small-size: .688rem;
            --mat-sys-label-small-tracking: .031rem;
            --mat-sys-label-small-weight: 500;
            --mat-sys-title-large: 400 1.375rem / 1.75rem Cinzel;
            --mat-sys-title-large-font: Cinzel;
            --mat-sys-title-large-line-height: 1.75rem;
            --mat-sys-title-large-size: 1.375rem;
            --mat-sys-title-large-tracking: 0;
            --mat-sys-title-large-weight: 400;
            --mat-sys-title-medium: 500 1rem / 1.5rem Raleway;
            --mat-sys-title-medium-font: Raleway;
            --mat-sys-title-medium-line-height: 1.5rem;
            --mat-sys-title-medium-size: 1rem;
            --mat-sys-title-medium-tracking: .009rem;
            --mat-sys-title-medium-weight: 500;
            --mat-sys-title-small: 500 .875rem / 1.25rem Raleway;
            --mat-sys-title-small-font: Raleway;
            --mat-sys-title-small-line-height: 1.25rem;
            --mat-sys-title-small-size: .875rem;
            --mat-sys-title-small-tracking: .006rem;
            --mat-sys-title-small-weight: 500
        }

        html {
            --mat-sys-corner-extra-large: 28px;
            --mat-sys-corner-extra-large-top: 28px 28px 0 0;
            --mat-sys-corner-extra-small: 4px;
            --mat-sys-corner-extra-small-top: 4px 4px 0 0;
            --mat-sys-corner-full: 9999px;
            --mat-sys-corner-large: 16px;
            --mat-sys-corner-large-end: 0 16px 16px 0;
            --mat-sys-corner-large-start: 16px 0 0 16px;
            --mat-sys-corner-large-top: 16px 16px 0 0;
            --mat-sys-corner-medium: 12px;
            --mat-sys-corner-none: 0;
            --mat-sys-corner-small: 8px
        }

        html {
            --mat-sys-dragged-state-layer-opacity: .16;
            --mat-sys-focus-state-layer-opacity: .12;
            --mat-sys-hover-state-layer-opacity: .08;
            --mat-sys-pressed-state-layer-opacity: .12
        }
    </style>
    <link rel="stylesheet" href="/angular-assets/styles-KWZNXJUB.css" media="print" onload="this.media='all'"><noscript>
        <link rel="stylesheet" href="/angular-assets/styles-KWZNXJUB.css">
    </noscript>
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" as="style">
</head>

<body class="mat-typography">
    <dashboard-root></dashboard-root>
    <link rel="modulepreload" href="/angular-assets/chunk-ZI2J7BMZ.js">
    <script src="/angular-assets/polyfills-ETSPKSUQ.js" type="module"></script>
    <script src="/angular-assets/main-47PN7CCI.js" type="module"></script>
</body>

</html>