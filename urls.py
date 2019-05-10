from django.urls import path
from django.conf.urls import include, url

from api import views

urlpatterns = [
    # path('', app_views.home, name='home'),
    url(r'^auth/', include('djoser.urls')),
    # url(r'^auth/', include('djoser.urls.authtoken')),
    url(r'^auth/', include('djoser.urls.jwt')),

    # **************************** USERS *******************************
    path('add_profile/', views.AddProfile.as_view(), name='api_add_profile'),
    path('profile_data/<int:user_id>', views.ProfileData.as_view(), name='api_profile_data'),
    path('edit_profile/', views.EditProfile.as_view(), name='api_modify_profile'),
    path('user_data/', views.UserData.as_view(), name='api_user_data'),
    path('change_password/', views.ChangePassword.as_view(), name='api_change_password'),
    path('users/', views.UserListView.as_view(), name='api_users_list'),
    path('follow_user/<int:user_id>', views.FollowUser.as_view(), name='api_follow_user'),
    path('following/', views.FollowingListView.as_view(), name='api_following'),
    path('change-level/<int:user_id>', views.ChangeUserLevelView.as_view(), name='api_change_user_level'),
    path('user_get_content/<int:course_id>/<int:level_id>', views.UserGetContentView.as_view(),
         name='api_get_user_content'),
    path('user_get_message/<int:message_id>', views.UserGetMessageView.as_view(),
         name='api_user_get_message'),
    path('get_profile/<int:user_id>', views.UserGetProfile.as_view(), name='api_get_profile'),
    path('drawing/', views.DrawingView.as_view(), name='api_drawing_view'),
    path('drawing_upload/<int:room_id>/<int:user_id>', views.UploadDrawView.as_view(), name='api_upload_draw'),
    path('drawing_post/<int:post_id>/<int:user_id>', views.UploadDrawPostView.as_view(), name='api_draw_post'),
    path('drawing_review/<int:evidence_id>', views.UploadDrawHomework.as_view(), name='api_draw_review'),
    path('get_image/<int:post_id>', views.BringImage.as_view(), name='api_get_image'),
    path('is_instructor/<int:user_id>', views.IsInstructorView.as_view(), name='api_is_instructor'),
    # **************************** COURSES *******************************
    path('add_course/', views.AddCourse.as_view(), name='api_add_course'),
    path('add_base_course/', views.AddBaseCourse.as_view(), name='api_add_base_course'),
    path('modify_course/<int:course_id>', views.ModifyCourse.as_view(), name='api_modify_course'),
    path('courses/', views.Courses.as_view(), name='api_courses'),
    path('courses/<int:course_id>', views.CourseDetail.as_view(), name='api_course_detail'),
    path('delete_course/<int:course_id>', views.DeleteCourse.as_view(), name='api_delete_course'),
    path('my_courses/', views.MyCourseList.as_view(), name='api_my_course_list'),
    path('category-levels/<int:user_id>', views.CategoryLevelsView.as_view(), name='api_category_levels'),
    path('student_teacher_courses/<int:user_id>/<int:student_id>', views.UserTeacherCourses.as_view(),name='api_user_teacher_courses'),

    # **************************** USER COURSES *******************************
    path('enroll_user_course/', views.EnrollUserCourse.as_view(), name="api_enroll_user_course"),
    path('unroll_user_course/<int:student_id>/<int:course_id>', views.UnrollStudent.as_view(),
         name="api_unroll_user_course"),
    path('user_course_data/<int:student_id>/<int:course_id>', views.UserCourseData.as_view(), name='api_user_course'),
    path('pending_request/<int:instructor_id>', views.PendingRequest.as_view(), name="api_pending_request"),
    path('approve_student_request/<int:student_id>/<int:course_id>', views.ApproveStudentRequest.as_view(),
         name='api_approve_student'),
    path('all-courses/<int:user_id>', views.CoursesUserView.as_view()),
    # **************************** CATEGORIES *******************************
    path('categories/', views.Categories.as_view(), name='api_categories'),

    # **************************** INSTRUCTORS ******************************
    path('get_instructors/', views.Instructors.as_view(), name='api_instructors'),

    # **************************** UNITS *******************************
    path('add_unit/', views.AddUnit.as_view(), name='api_add_unit'),
    path('edit_unit/<int:unit_id>', views.EditUnit.as_view(), name="api_edit_unit"),
    path('units/<int:course_id>', views.Units.as_view(), name='api_units'),
    path('unit_detail/<int:unit_id>', views.UnitDetail.as_view(), name="api_unit_detail"),
    path('delete_unit/<int:unit_id>', views.DeleteUnit.as_view(), name='api_unit_delete'),

    # **************************** LEVELS *******************************
    path('add_level/', views.AddLevel.as_view(), name="api_add_level"),
    path('edit_level/<int:level_id>', views.EditLevel.as_view(), name="api_edit_level"),
    path('levels/<int:course_id>', views.Levels.as_view(), name="api_levels"),
    path('level_detail/<int:level_id>', views.LevelDetail.as_view(), name="api_level_detail"),
    path('delete_level/<int:level_id>', views.DeleteLevel.as_view(), name='api_level_delete'),
    path('promote_level/<int:homework_id>/<int:student_id>', views.PromoteLevel.as_view(),
         name='api_promote_level'),
    path('relegated_levels/<int:homework_id>/<int:student_id>', views.RelegatedLevels.as_view(),
         name='api_relegated_levels'),
    path('relegate_level/<int:homework_id>/<int:student_id>/<int:num>', views.RelegateLevel.as_view(),
         name='api_relegate_level'),
    path('course_levels/<int:course_id>', views.CourseLevels.as_view(), name='api_course_levels'),

    # **************************** LECTURES *******************************
    path('deliverables/<int:level_id>', views.Deliverables.as_view(), name='api_deliverables'),

    # **************************** LECTURES *******************************
    path('add_lecture/', views.AddLecture.as_view(), name='api_add_lecture'),
    path('edit_lecture/<int:lecture_id>', views.EditLecture.as_view(), name='api_edit_lecture'),
    path('lecture_detail/<int:lecture_id>', views.LectureDetail.as_view(), name='api_lectures'),
    path('delete_lecture/<int:lecture_id>', views.DeleteLecture.as_view(), name='api_delete_lecture'),

    # **************************** EXERCISES *******************************
    path('add_exercise/', views.AddExercise.as_view(), name='api_add_exercise'),
    path('edit_exercise/<int:exercise_id>', views.EditExercise.as_view(), name='api_edit_exercise'),
    path('exercise_detail/<int:exercise_id>', views.ExerciseDetail.as_view(), name='api_exercises'),
    path('delete_exercise/<int:exercise_id>', views.DeleteExercise.as_view(), name='api_delete_exercise'),

    # **************************** HOME WORKS *******************************
    path('add_home_work/', views.AddHomeWork.as_view(), name='api_add_home_work'),
    # path('home_works/<int:level_id>', views.HomeWorks.as_view(), name='api_homer_works'),
    path('edit_home_work/<int:home_work_id>', views.EditHomeWork.as_view(), name='api_edit_home_work'),
    path('home_work_detail/<int:home_work_id>', views.HomeWorkDetail.as_view(), name='api_home_work_detail'),
    path('delete_home_work/<int:home_work_id>', views.DeleteHomeWork.as_view(), name='api_delete_home_work'),
    path('home_work_evidence/<int:home_work_id>', views.HomeworkEvidenceView.as_view(),
         name='api_add_home_work_evidence'),
    path('homework-students/<int:homework_id>', views.HomeWorkStudentsView.as_view(), name='api_homework_students'),
    path('student-evidence/<int:homework_id>/<int:student_id>', views.StudentHomeWorkDetailView.as_view(),
         name='api_student_evidence'),
    path('review-evidence/<int:evidence_id>', views.ReviewHomeworkEvidence.as_view(), name='api_review_evidence'),
    path('review-video-evidence/<int:home_work_id>', views.ReviewVideoHomeworkEvidence.as_view(),
         name='api_review_video_evidence'),
    path('send-audio-evidence/<int:homeworkId>/<int:studentId>', views.HomeworkAudioEvidence.as_view(),
         name='api_add_home_work_audio_evidence'),
    path('send-audio-evidence-review/<int:homeworkId>/<int:studentId>', views.ReviewAudioHomeworkEvidence.as_view(),
         name='api_review_audio_home_work_evidence'),
    path('get_evidence/<int:evidence_id>', views.UserEvidence.as_view(), name='api_user_evidence'),
    path('add_review/<int:evidence_id>', views.UserReview.as_view(), name='api_user_review'),
    path('add_video-review/<int:evidence_id>', views.AddVideoReview.as_view(), name='api_video_review'),

    # **************************** USER POSTS *******************************
    path('posts/', views.UserPostsView.as_view(), name='api_add_post'),
    path('delete-post/<int:post_id>', views.DeletePostView.as_view(), name='api_delete_post'),
    path('like-post/<int:post_id>', views.PostLikeView.as_view(), name='api_like_post'),
    #path('post-comments/<int:post_id>', views.PostCommentView.as_view(), name='api_post_comment'),
    path('post-comments/<int:postId>', views.PostCommentView.as_view(), name='api_post_comment'),
    path('post-comment-audio/<int:post_id>', views.PostCommentAudio.as_view(), name='api_post_comment_audio'),
    path('delete-comment/<int:comment_id>', views.DeleteCommentView.as_view(), name='api_delete_comment'),
    path('delete-comment-content/<int:comment_id>/<int:option>', views.DeleteCommentContent.as_view(),
         name='api_delete_comment_content'),
    path('feed/', views.FeedView.as_view(), name='api_feed'),
    path('recommended-posts/', views.RecommendedPosts.as_view(), name='api_feed_posts'),
    path('get_post/<int:post_id>', views.UserPost.as_view(), name='api_get_post'),
    path('post-audio/<int:profile_id>/<int:user_id>', views.UserPostAudio.as_view(), name='api_post_audio'),
    path('post-video/<int:profile_id>/<int:user_id>', views.PostVideo.as_view(), name='api_post_video'),
    path('delete-post-content/<int:post_id>/<int:option>', views.DeletePostContent.as_view(),
         name='api_delete_post_content'),

    # **************************** USER PAYMENTS *******************************
    path('payment/<int:course_id>', views.Payment.as_view(), name='api_payment'),

    # **************************** NOTIFICATIONS *******************************
    path('notifications/<int:user_id>', views.Notifications.as_view(), name='api_notifications'),
    path('notifications_number/<int:user_id>', views.NotificationsNumber.as_view(), name='api_notifications_number'),
    path('delete_notifications/<int:user_id>', views.DeleteNotifications.as_view(), name='api_delete_notifications'),
    path('student-notifications/<int:student_id>/<int:user_id>', views.StudentNotificationsView.as_view(),
         name='api_student_notifications'),
    path('see-notifications/<int:notification_id>', views.SeeNotificationsView.as_view(), name='api_see_notifications'),
    path('see-course-notification/<int:course_notification_id>', views.SeeCourseNotifications.as_view(),
         name='api_course_notification'),
    path('course-notifications/<int:student_id>/<int:course_id>', views.CourseNotification.as_view(),
         name='api_course_notifications'),
    path('resolve-notification/<int:notification_id>', views.ResolveNotification.as_view(),
         name="api_resolve_notification"),

    # ******************************** CHATS ***********************************
    path('get-chats/<int:user_id>', views.Chats.as_view(), name='api_chats'),
    path('new-chat/<int:sender_id>/<int:destiny_id>', views.NewChat.as_view(), name='api_new_chat'),
    path('delete-chat/<int:room_id>', views.DeleteChat.as_view(), name='api_delete_chat'),
    path('chats_number/<int:user_id>', views.ChatsNumber.as_view(), name='api_chats_number'),

    # ****************************** MESSAGES **********************************
    path('message/<int:room_id>/<int:user_id>', views.Messages.as_view(), name='api_messages'),
    path('send_message/<int:room_id>/<int:user_id>', views.NewMessage.as_view(), name='api_new_message'),
    path('messages_notifications/<int:user_id>/<int:peer_id>/<int:notification_id>',
         views.MessagesNotifications.as_view(), name='api_messages_notifications'),
    path('audio_message/<str:filename>/<int:room_id>/<int:user_id>', views.NewAudio.as_view(), name='api_new_audio'),
    path('video-message/<int:room_id>/<int:user_id>', views.SendVideo.as_view(), name='api_new_video'),
    path('send-audio/<int:room_id>/<int:user_id>', views.SendAudio.as_view(), name='api_send_audio'),
    path('delete-message/<int:message_id>', views.DeleteMessage.as_view(), name='api_delete_message'),
    path('delete-message-content/<int:message_id>/<int:option>', views.DeleteMessageContent.as_view(),
         name='api_delete_message_content'),

    # ********************************** SECTION *********************************

    path('add-section/<int:level_id>', views.AddSection.as_view(), name='api_add_section'),
    path('edit-section/<int:section_id>', views.EditSection.as_view(), name='api_edit_section'),
    path('delete-section/<int:section_id>', views.DeleteSection.as_view(), name='api_delete_section'),
    path('add_section_image/<int:section_id>', views.AddSectionImage.as_view(), name='api_add_image'),
    path('edit_image_section/<int:image_id>', views.EditImageSection.as_view(), name='api_edit_section_image'),
    path('delete_image_section/<int:image_id>', views.DeleteImageSection.as_view(), name='api_delete_section_image'),
    path('section_detail/<int:section_id>', views.SectionDetail.as_view(), name='api_section_detail'),

    # *********************************** QUIZES *********************************

    path('add-quiz/<int:user_id>/<int:level_id>', views.CreateQuiz.as_view(), name='api_new_quiz'),
    path('edit-quiz/<int:quiz_id>', views.EditQuiz.as_view(), name='api_edit_quiz'),
    path('delete-quiz/<int:quiz_id>', views.DeleteQuiz.as_view(), name='api_delete_quiz'),
    path('quiz-students/<int:quiz_id>', views.GetStudents.as_view(), name='api_quiz_questions'),
    path('quiz-question/<int:quiz_id>', views.QuizQuestions.as_view(), name='api_quiz_questions'),

    # ********************************* QUESTIONS *********************************

    path('add-question/<int:quiz_id>', views.AddQuestion.as_view(), name='api_new_question'),
    path('edit-question/<int:question_id>', views.EditQuestion.as_view(), name='api_edit_question'),
    path('delete-question/<int:question_id>', views.DeleteQuestion.as_view(), name='api_delete_question'),
    path('get-question/<int:question_id>/<int:quiz_id>', views.GetQuestion.as_view(), name='api_get_question'),

    # ********************************* ANSWERS ***********************************

    path('add-answer/<int:question_id>/<int:student_id>/<int:quiz_id>', views.AddAnswer.as_view(),
         name='api_add_question'),
    path('student-answers/<int:quiz_id>/<int:student_id>', views.GetAnswers.as_view(), name='api_student_answers'),
    path('add-grade/<int:quiz_id>/<int:student_id>', views.GradeStudent.as_view(), name='api_grade_student'),
    path('get-grade/<int:quiz_id>/<int:student_id>', views.GetGrade.as_view(), name='api_get_grade'),
    path('create-quiz-notification/<int:quiz_id>/<int:student_id>', views.CreateQuizNotification.as_view(),
         name='api_create_quiz_notification'),

    # ****************************** QUICK ANSWERS **********************************

    path('add-global/<int:user_id>', views.AddGlobal.as_view(), name='api_add_global'),
    path('globals/<int:user_id>', views.GlobalQuestions.as_view(), name='api_globals'),
    path('course_quicks/<int:user_id>/<int:course_id>', views.CourseQuickAnswers.as_view(),
         name='api_course_quick_answers'),
    path('edit-global/<int:quick_id>', views.EditGlobal.as_view(), name='api_edit_globals'),
    path('delete-global/<int:quick_id>', views.DeleteGlobal.as_view(), name='api_delete_globals'),
    path('add-course-quick/<int:user_id>/<int:course_id>', views.AddCourseQuick.as_view(), name='api_add_course_quick'),
    path('edit-course-quick/<int:quick_id>', views.EditCourseQuick.as_view(), name='api_edit_course_quick'),
    path('delete-course-quick/<int:quick_id>', views.DeleteCourseQuick.as_view(), name='api_delete_course_quick'),
]
