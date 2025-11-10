<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Company;

use App\Models\CompanyReview;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CompanyReviewListScreen extends Screen
{
    /**
     * Restrict access to the existing company permission.
     *
     * @var string[]
     */
    public $permission = ['platform.companies'];

    public $name = 'Company Reviews';

    public $description = 'Monitor and moderate culture survey submissions.';

    public function query(): iterable
    {
        return [
            'reviews' => CompanyReview::with('company')
                ->latest()
                ->paginate(20),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('reviews', [
                TD::make('company.name', 'Company')
                    ->render(function (CompanyReview $review) {
                        if (! $review->company) {
                            return '<span class="text-muted">Removed</span>';
                        }

                        return Link::make($review->company->name)
                            ->route('platform.companies.edit', $review->company);
                    })
                    ->sort(),
                TD::make('reviewer_role', 'Reviewer')
                    ->render(fn (CompanyReview $review) => e($review->reviewer_name ?? 'Anonymous').' · '.e($review->reviewer_role ?? 'Team member')),
                TD::make('overall_rating', 'Overall')
                    ->render(fn (CompanyReview $review) => number_format($review->overall_rating, 1)),
                TD::make('would_recommend', 'Recommend')
                    ->render(fn (CompanyReview $review) => $review->would_recommend ? 'Yes' : 'No'),
                TD::make('created_at', 'Submitted')
                    ->render(fn (CompanyReview $review) => $review->created_at->format('M d, Y')),
                TD::make('highlights', 'Highlights')
                    ->render(fn (CompanyReview $review) => str($review->highlights)->limit(60)->toString())
                    ->defaultHidden(),
                TD::make(__('Actions'))
                    ->align(TD::ALIGN_RIGHT)
                    ->render(fn (CompanyReview $review) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('View company page')
                                ->icon('bs.box-arrow-up-right')
                                ->route('companies.show', $review->company)
                                ->target('_blank'),
                            Button::make('Delete review')
                                ->icon('bs.trash3')
                                ->confirm('Remove this review?')
                                ->method('remove', [
                                    'review' => $review->id,
                                ]),
                        ])),
            ]),
        ];
    }

    public function remove(CompanyReview $review)
    {
        $company = $review->company;
        $review->delete();

        if ($company) {
            $company->refreshAggregatedRating();
        }

        Toast::info('Review deleted.');
    }
}
