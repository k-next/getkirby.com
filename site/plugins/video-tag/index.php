<?php

use Kirby\Cms\App as Kirby;
use Kirby\Text\KirbyTag;
use Kirby\Toolkit\F;
use Kirby\Toolkit\Html;
use Kirby\Toolkit\Str;

Kirby::plugin('getkirby/video-tag', [
    'tags' => [
        'video' => [
            'attr' => [
                'autoplay',
                'caption',
                'controls',
                'class',
                'height',
                'loop',
                'muted',
                'poster',
                'preload',
                'style',
                'width',
            ],
            'html' => function ($tag) {
                // available attributes
                $attrs = KirbyTag::$types[$tag->type]['attr'];

                // gets global video tag options
                $options = $tag->kirby()->option('kirbytext.video', []);

                // injects default values
                // applies only defined attributes to safely update tag props
                foreach ($options as $option => $value) {
                    if (
                        in_array($option, $attrs) === true &&
                        (isset($tag->{$option}) === false || $tag->{$option} === null)
                    ) {
                        $tag->{$option} = $value;
                    }
                }

                // generates tag options helper
                $generateTagOptions = function ($tag) {
                    // checks local poster file and handle
                    if (
                        empty($tag->poster) === false &&
                        Str::startsWith($tag->poster, 'http://') !== true &&
                        Str::startsWith($tag->poster, 'https://') !== true
                    ) {
                        if ($poster = $tag->file($tag->poster)) {
                            $tag->poster = $poster->url();
                        }
                    }

                    return [
                        'autoplay' => $autoplay = Str::toType($tag->autoplay, 'bool'),
                        'controls' => Str::toType($tag->controls ?? true, 'bool'),
                        'height'   => $tag->height,
                        'loop'     => Str::toType($tag->loop, 'bool'),
                        'muted'    => Str::toType($tag->muted ?? $autoplay, 'bool'),
                        'poster'   => $tag->poster,
                        'preload'  => $tag->preload,
                        'width'    => $tag->width
                    ];
                };

                // handles local video file
                if (
                    Str::startsWith($tag->value, 'http://') !== true &&
                    Str::startsWith($tag->value, 'https://') !== true
                ) {
                    if ($tag->file = $tag->file($tag->value)) {
                        $options = $generateTagOptions($tag);
                        $source = Html::tag('source', null, [
                            'src'  => $tag->file->url(),
                            'type' => $tag->file->mime()
                        ]);
                        $video = Html::tag('video', [$source], $options);
                    }
                } else {
                    // first handles supported video providers as youtube, vimeo, etc
                    try {
                        $video = Html::video(
                            $tag->value,
                            $options,
                            [
                                'height' => $tag->height,
                                'width'  => $tag->width
                            ]
                        );
                    } catch (Exception $e) {
                        // if not one of the supported video providers
                        // it checks if there is a valid remote video file
                        $extension = F::extension($tag->value);
                        $type      = F::extensionToType($extension);
                        $mime      = F::extensionToMime($extension);

                        if ($type === 'video') {
                            $options = $generateTagOptions($tag);
                            $source = Html::tag('source', null, [
                                'src'  => $tag->value,
                                'type' => $mime
                            ]);
                            $video = Html::tag('video', [$source], $options);
                        }
                    }
                }

                return Html::figure([$video ?? ''], $tag->caption, [
                    'class' => $tag->class ?? 'video',
                    'style' => '--aspect-ratio: 16/9; ' . $tag->style
                ]);
            }
        ]
    ]
]);
