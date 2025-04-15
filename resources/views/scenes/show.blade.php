@extends('layouts.app')

@section('content')

<style>
  .scene-title {
      text-align: center;
      margin-bottom: 20px;
  }

  .background-image {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-size: cover;
      background-position: center;
      opacity: 0.2;
      z-index: -1;
  }

  .description {
      margin-bottom: 20px;
  }

  .expandable-description {
      height: 100px;
      overflow: hidden;
      transition: height 0.3s ease;
  }

  .expandable-description.expanded {
      height: auto;
  }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="scene-title">{{ $scene->title }}</h2>
                </div>
                <div class="card-body">
                    <p><strong>ID#:</strong> {{ $scene->id }}</p>
                    <p><strong>Location:</strong> {{ $scene->location }}</p>
                    <div class="description">
                        <strong>Description:</strong>
                        <p class="expandable-description">{{ $scene->description }}</p>
                        <button class="btn btn-sm btn-primary expand-button">Expand</button>
                    </div>

                    @if($scene->location_image)
                        <div class="background-image" style="background-image: url('{{ asset('images/' . $scene->location_image) }}')"></div>
                    @endif

                    <hr>

                    @auth
                        @if(auth()->user()->id == $scene->creator_id)
                            <div class="scene-management-tools">
                                <h3>Scene Management Tools</h3>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#editTitleModal">Edit Title</button>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#editLocationModal">Edit Location</button>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#editPlotIDModal">Edit PlotID</button>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#editSynopsisModal">Edit Synopsis</button>
                                <form action="{{ route('scenes.complete', $scene) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">End Scene/Close Scene</button>
                                </form>
                            </div>
                        @elseif(!$isParticipating && $userCharacters->count() > 0) {{-- Check if user is not participating AND has characters --}}
                            <div class="join-scene">
                                <h5>Join Scene</h5>
                                <form action="{{ route('scenes.join', $scene) }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-2">
                                        <label for="character_id">Select Character:</label>
                                        <select name="character_id" id="character_id" class="form-control" required>
                                            @foreach($userCharacters as $character)
                                                <option value="{{ $character->id }}">{{ $character->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success">Join Scene</button>
                                </form>
                            </div>
                        @elseif($userCharacters->count() == 0)
                             <div class="join-scene">
                                <p>You need an active character to join this scene. <a href="{{ route('characters.create') }}">Create one?</a></p>
                            </div>
                        @endif

                        @if($isParticipating) {{-- Use the existing $isParticipating variable --}}
                            <div class="scene-participation-tools mt-3">
                                <h3>Scene Participation Tools</h3>
                                <div class="vote-for-character">
                                    <h5>Vote for a Character</h5>
                                    <form action="{{ route('scenes.vote', $scene) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="voted_character_id">Select a Character to Vote For:</label>
                                            <select name="voted_character_id" id="voted_character_id" class="form-control" required>
                                                @foreach($scene->participants as $participant)
                                                    @if($participant->user_id != Auth::id())
                                                        <option value="{{ $participant->character_id }}">{{ $participant->character->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-info">Vote</button>
                                     </form>
                                </div>
                                <div class="post-pose">
                                    <h5>Post a Pose</h5>
                                    <form action="{{ route('posts.store', $scene) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="content">Pose Description:</label>
                                            <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                                            <input type="hidden" name="character_id" value="{{ $userCharacters->first()->id }}">
                                        </div>
                                        <button type="submit" class="btn btn-info">Post Pose</button>
                                     </form>
                                </div>
                                <h5>Your Poses</h5>
                                @if($scene->posts()->where('character_id', $userCharacters->first()->id)->exists())
                                    <ul>
                                        @foreach($scene->posts()->where('character_id', $userCharacters->first()->id)->get() as $post)
                                            <li>
                                                {{ $post->content }}
                                                <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>You haven't posted any poses in this scene yet.</p>
                                @endif
                                <button class="btn btn-info roll-dice-button">Roll dice</button>
                                <div class="dice-result"></div>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals for editing scene properties -->
<div class="modal fade" id="editTitleModal" tabindex="-1" role="dialog" aria-labelledby="editTitleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editTitleModalLabel">Edit Title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('scenes.update', $scene) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body">
              <div class="form-group">
                  <label for="title">New Title</label>
                  <input type="text" class="form-control" id="title" name="title" value="{{ $scene->title }}" required>
                  {{-- Include other fields required by the update method's validation, even if hidden or read-only, if necessary --}}
                  {{-- Based on SceneController@update validation, we need description, location_id, and optionally plot_id, is_private --}}
                  <input type="hidden" name="description" value="{{ $scene->description }}">
                  <input type="hidden" name="location_id" value="{{ $scene->location_id }}">
                  @if($scene->plot_id)
                  <input type="hidden" name="plot_id" value="{{ $scene->plot_id }}">
                  @endif
                  <input type="hidden" name="is_private" value="{{ $scene->is_private ? 1 : 0 }}">
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="editLocationModal" tabindex="-1" role="dialog" aria-labelledby="editLocationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editLocationModalLabel">Edit Location</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('scenes.update', $scene) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body">
              <div class="form-group">
                  <label for="location_id">New Location</label>
                  <select name="location_id" id="location_id" class="form-control" required>
                      @foreach(\App\Models\Location::orderBy('name')->get() as $location)
                          <option value="{{ $location->id }}" {{ $scene->location_id == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                      @endforeach
                  </select>
                  {{-- Include other fields required by the update method's validation, even if hidden or read-only, if necessary --}}
                  <input type="hidden" name="title" value="{{ $scene->title }}">
                  <input type="hidden" name="description" value="{{ $scene->description }}">
                  @if($scene->plot_id)
                  <input type="hidden" name="plot_id" value="{{ $scene->plot_id }}">
                  @endif
                  <input type="hidden" name="is_private" value="{{ $scene->is_private ? 1 : 0 }}">
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="editPlotIDModal" tabindex="-1" role="dialog" aria-labelledby="editPlotIDModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editPlotIDModalLabel">Edit PlotID</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('scenes.update', $scene) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body">
              <div class="form-group">
                  <label for="plot_id">New Plot</label>
                  <select name="plot_id" id="plot_id" class="form-control">
                      <option value="">None</option>
                      @foreach(\App\Models\Plot::orderBy('title')->get() as $plot)
                          <option value="{{ $plot->id }}" {{ $scene->plot_id == $plot->id ? 'selected' : '' }}>{{ $plot->title }}</option>
                      @endforeach
                  </select>
                  {{-- Include other fields required by the update method's validation, even if hidden or read-only, if necessary --}}
                  <input type="hidden" name="title" value="{{ $scene->title }}">
                  <input type="hidden" name="description" value="{{ $scene->description }}">
                  <input type="hidden" name="location_id" value="{{ $scene->location_id }}">
                  <input type="hidden" name="is_private" value="{{ $scene->is_private ? 1 : 0 }}">
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="editSynopsisModal" tabindex="-1" role="dialog" aria-labelledby="editSynopsisModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editSynopsisModalLabel">Edit Synopsis</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('scenes.update', $scene) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body">
              <div class="form-group">
                  <label for="description">New Synopsis</label>
                  <textarea class="form-control" id="description" name="description" rows="5" required>{{ $scene->description }}</textarea>
                  {{-- Include other fields required by the update method's validation, even if hidden or read-only, if necessary --}}
                  <input type="hidden" name="title" value="{{ $scene->title }}">
                  <input type="hidden" name="location_id" value="{{ $scene->location_id }}">
                  @if($scene->plot_id)
                  <input type="hidden" name="plot_id" value="{{ $scene->plot_id }}">
                  @endif
                  <input type="hidden" name="is_private" value="{{ $scene->is_private ? 1 : 0 }}">
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
      </form>
    </div>
  </div>
</div>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editPlotIDModalLabel">Edit PlotID</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Form for editing PlotID -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editSynopsisModal" tabindex="-1" role="dialog" aria-labelledby="editSynopsisModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editSynopsisModalLabel">Edit Synopsis</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Form for editing synopsis -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.expand-button').click(function() {
            $('.expandable-description').toggleClass('expanded');
            if ($('.expandable-description').hasClass('expanded')) {
                $(this).text('Collapse');
            } else {
                $(this).text('Expand');
            }
        });

        $('.roll-dice-button').click(function() {
            $.ajax({
                url: '/roll-dice',
                type: 'GET',
                success: function(data) {
                    $('.dice-result').text('Result: ' + data.result);
                }
            });
        });
    });
</script>
@endsection

