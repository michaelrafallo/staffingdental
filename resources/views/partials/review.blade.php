<?php 

    $review  = App\Post::where('post_title', $info->id)->where('post_author', $user_id)->where('post_type', 'review');
    $reviews = App\Post::where('post_title', $info->id)->where('post_type', 'review')->orderBy('id', 'DESC')->paginate(15);

    $selects = array('overall', 'medical_competence', 'punctuality', 'beside_manner', 'rating');
    
    $review_count = App\Post::search([], ['overall'], ['overall'])->where('post_title', $info->id)->where('post_type', 'review');



    $all_reviews_count = ($review_count->count()) ? $review_count->count() : 1;
    $overall_reviews = $review_count->get()->SUM('overall') / $all_reviews_count;

    $count_review = $review->count();
    $info_review = $review->first();



    $book_job_count = App\Post::whereIn('post_type', ['booking', 'application'])
                              ->whereIn('post_status', ['completed', 'for_approval', 'confirmed', 'hired', 'invited'])
                              ->where('post_title', $info->id)
                              ->where('post_author', $user_id)
                              ->orWhereIn('post_type', ['booking', 'application'])
                              ->whereIn('post_status', ['completed', 'for_approval', 'confirmed', 'hired', 'invited'])
                              ->where('post_author', $info->id)
                              ->where('post_title', $user_id)
                              ->count();

?>

<style>
.reviews .img-thumbnail {
    width:100px;
    height:100px;    
}
.form-review {
    background: #fafafa;
    border: 1px solid #bfbfbf;
    padding: 0px 20px 10px;    
}    
</style>

<div class="portlet light bordered">
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-3">

                @if($user->group == 'provider')
                MEDICAL COMPETENCE: <strong>{{ number_format($review_count->get()->SUM('medical_competence') / $all_reviews_count, 1) }}</strong><br>
                PUNCTUALITY: <strong>{{ number_format($review_count->get()->SUM('punctuality') / $all_reviews_count, 1) }}</strong><br>
                BEDSIDE MANNER: <strong>{{ number_format($review_count->get()->SUM('beside_manner') / $all_reviews_count, 1) }}</strong><br>
                @endif

                <span class="text-primary sbold">OVERALL RATING: {{ number_format($overall_reviews, 1) }}</span>
                <div class="text-warning margin-top-10 h4">
                    {{ stars_review($overall_reviews) }}              
                </div>
            </div>
            <div class="col-md-9 reviews">


            @if($user_id != $info->id)
            <form method="post" action="{{ URL::route('backend.review.write') }}" class="form-submit">
                {{ csrf_field() }}
                <input type="hidden" name="uid" value="{{ $info->id }}">
                <div class="form-group">

                    @if( $count_review )
                    <?php 
                    $my_medical_competence  = $info_review->get_meta($info_review->id, 'medical_competence');
                    $my_punctuality         = $info_review->get_meta($info_review->id, 'punctuality');
                    $my_beside_manner       = $info_review->get_meta($info_review->id, 'beside_manner');
                    $my_rating              = $info_review->get_meta($info_review->id, 'rating');

                    $my_overall = $info_review->get_meta($info_review->id, 'overall');
                    ?>
                    <div class="my-review form-review">

                        <h3 class="uppercase">My Review</h3>
                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{ has_photo($info->get_meta($user_id, 'profile_picture')) }}" class="img-circle img-thumbnail">     
                                <div class="margin-top-20">
                                    
                                    @if($user->group == 'provider')
                                    MEDICAL COMPETENCE: <strong>{{ number_format($my_medical_competence, 1) }}</strong><br>
                                    PUNCTUALITY: <strong>{{ number_format($my_punctuality, 1) }}</strong><br>
                                    BEDSIDE MANNER: <strong>{{ number_format($my_beside_manner, 1) }}</strong><br>
                                    @endif

                                    <span class="text-primary sbold">OVERALL RATING: {{ $my_overall }}</span>
                                    <div class="text-warning margin-top-10 h4">
                                        {{ stars_review($my_overall) }}          
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">

                                        <a href="#" class="delete btn btn-danger uppercase"
                                            data-href="{{ URL::route('backend.review.delete', $info_review->id) }}" 
                                            data-toggle="modal"
                                            data-target=".delete-modal" 
                                            data-title="Delete my Review"
                                            data-body="Are you sure you want to delete your review?"><i class="fa fa-trash"></i> Delete</a>

                                <a href="" class="btn btn-success uppercase btn-review" data-target="write-review"><i class="fa fa-pencil"></i> Edit</a>
                                <h4 class="no-margin sbold margin-top-20">{{ ucwords($info->fullname) }}</h4>
                                <p class="margin-top-20 text-justify">
                                @if( $info_review->post_content )
                                {{ $info_review->post_content }}
                                @else
                                    <h4>
                                    <a href="" class=" btn-review" data-target="write-review"><i class="fa fa-pencil"></i> Write a review</a>     
                                    </h4>               
                                @endif
                                </p>
                            </div>
                        </div>            
                    </div>
                    @endif

                    <div class="write-review form-review" style="{{ ($count_review) ? 'display:none;' : '' }}">
                        <h3 class="uppercase">Write a Review</h3>
                        <div class="form-group">
                            <textarea name="review" class="form-control review" rows="5" placeholder="Write your review here">{{ @$info_review->post_content }}</textarea>
                        </div>

                        <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <th></th>
                                <th>
                                    <div class="text-center">
                                        <i class="fa fa-star text-warning"></i>
                                        1
                                    </div>
                                </th>
                                <th>
                                    <div class="text-center">
                                        <i class="fa fa-star text-warning"></i>
                                        2
                                    </div>
                                </th>
                                </th>
                                <th>
                                    <div class="text-center">
                                        <i class="fa fa-star text-warning"></i>
                                        3
                                    </div>
                                </th>
                                </th>
                                <th>
                                    <div class="text-center">
                                        <i class="fa fa-star text-warning"></i>
                                        4
                                    </div>
                                </th>
                                </th>
                                <th>
                                    <div class="text-center">
                                        <i class="fa fa-star text-warning"></i>
                                        5
                                    </div>
                                </th>
                                </th>
                            </thead>
                            <tbody>

                                @if($user->group == 'provider')
                                <tr>
                                    <td>Medical Competence</td>

                                    @foreach(range(1, 5) as $mc)
                                    <td class="text-center">
                                        <label class="mt-radio">
                                        <input type="radio" name="medical_competence" value="{{ $mc }}" class="review" {{ checked($mc, @$my_medical_competence) }}>
                                        <span></span>
                                        </label>
                                    </td>
                                    @endforeach
                          
                                </tr>
                                <tr>
                                    <td>Punctuality</td>
                                    @foreach(range(1, 5) as $p)
                                    <td class="text-center">
                                        <label class="mt-radio">
                                        <input type="radio" name="punctuality" value="{{ $p }}" class="review" {{ checked($p, @$my_punctuality) }}>
                                        <span></span>
                                        </label>
                                    </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td>Beside Manner</td>
                                    @foreach(range(1, 5) as $bm)
                                    <td class="text-center">
                                        <label class="mt-radio">
                                        <input type="radio" name="beside_manner" value="{{ $bm }}" class="review" {{ checked($bm, @$my_beside_manner) }}>
                                        <span></span>
                                        </label>
                                    </td>
                                    @endforeach
                                </tr>
                                @else
                                <tr>
                                    <td>Rating</td>
                                    @foreach(range(1, 5) as $bm)
                                    <td class="text-center">
                                        <label class="mt-radio">
                                        <input type="radio" name="rating" value="{{ $bm }}" class="review" {{ checked($bm, @$my_rating) }}>
                                        <span></span>
                                        </label>
                                    </td>
                                    @endforeach
                                </tr>
                                @endif

                            </tbody>
                        </table>
                        </div>

                        <div class="pull-right">
                            @if($book_job_count==0)                            
                                <a href="#no-appointment" class="btn btn-primary btn-lg uppercase" data-toggle="modal">Submit</a>
                            @else
                               <button type="submit" class="btn btn-primary btn-lg uppercase submit-review" disabled="disabled">Submit</button>                    
                                <button class="btn btn-default btn-lg uppercase btn-review" data-target="my-review">Cancel</button>                    
                            @endif                       

                        </div>
                        
                        <div class="clearfix"></div>                
                    </div>
                </div>
            </form>

            <hr>
            @endif


                <h3 class="uppercase">Reviews</h3>
                @foreach($reviews as $rev)
                <?php $postmeta = get_meta($rev->postMetas()->get()); ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-left">
                            <img src="{{ has_photo($info->get_meta($rev->post_author, 'profile_picture')) }}" class="img-circle img-thumbnail">  
                        </div>
                        <div class="col-md-10">
                            <h4 class="sbold">

                            @if(Request::is('*/accounts/*') || Request::is('*/dashboard'))
                            <?php $url = (App\User::find($rev->post_author)->group == 'owner') ? 'provider.job-postings.employer' : 'owner.dentalpro.profile'; ?>
                            <a href="{{ URL::route($url, $rev->post_author) }}">{{ ucwords($info->find($rev->post_author)->fullname) }}</a>
                            @else
                            {{ ucwords($info->find($rev->post_author)->fullname) }}
                            @endif

                            </h4>
                            <p class="text-justify">{{ $rev->post_content }}</p>
                            <div class="text-warning h4">
                                <span class="text-muted">({{ $postmeta->overall }})</span>
                                {{ stars_review($postmeta->overall) }}          
                            </div>
                            <small class="text-muted">{{ time_ago($rev->updated_at) }} </small>
                        </div>
                    </div>
                </div>
                <hr>
                @endforeach

                @if(count($reviews) == 0) 
                    <h3 class="well text-center">No reviews at this moment.</h3>
                @endif

            </div>
        </div>
    </div>
</div>
