   M S B A M L   `   `   `  ����  SyncJar    syncjar.MainWindow   5   	   \ XPresentationFramework, Version=4.0.0.0, Culture=neutral, PublicKeyToken=31bf3856ad364e35W SPresentationCore, Version=4.0.0.0, Culture=neutral, PublicKeyToken=31bf3856ad364e35R NWindowsBase, Version=4.0.0.0, Culture=neutral, PublicKeyToken=31bf3856ad364e35D 9http://schemas.microsoft.com/winfx/2006/xaml/presentation    5   	   R NSystem.Xaml, Version=4.0.0.0, Culture=neutral, PublicKeyToken=b77a5c561934e089:x,http://schemas.microsoft.com/winfx/2006/xaml     5   	     � Title$  SyncJar��5   	   $	��510��6   $	��525��6&   ���#111155 � WindowStartupLocation$ CenterScreen=�6G    � 	Resources 5       �� 
TargetType �� Property �� Value��%m   '��       �� 5   
   " ��6   .�5      ��  �@��BitmapScalingMode$ HighQuality=�6@   5      5	      .��5
      � .+�5   
   �� -   5        $��login��5   G    �� Orientation$ Vertical=�6   $��Center=�6,   $��Center=�6T   $y�	Collapsed=�6q   .+�5      �� $��.\images\logo.png��6   $	��200��6/   $��0,-30pt,0,20ptq�6;   6U   �� 5      $ 
Horizontal=�6   $��Center=�62   .+�5      �� -   5        $��label1��5   @   ���#fff$��User Name :��6*   $��
0,0,10pt,0q�6N   �� 5      -   5        $��
usernameTB��5   &   $	��120��6   6&   5      �� 5      $ 
Horizontal=�6   $��Center=�62   $��
0,10pt,0,0q�6O   .+�5      �� -   5        $��label2��5   B   ���#fff$��Password :   ��6*   $��
0,0,10pt,0q�6P   G� 5      -   5        $��PasswordBoxPB��5      $	��120��63   5      �� 5      -   5        $��error��5   &   ���#fff$��Center=�63   $	��5ptq�6P   $y�Hidden=�6]   �� 5      -   5        $��Log in��5      $��23��6&   $��75��62   $��Center=�6T   5      �� 5   
   -   5        $��	logoutBTN��5   /   $��Log out��6   $��23��6#   $	��Top=�6@   $
��20ptq�6X   $y�	Collapsed=�6f   $��Right=�6}   $��75��6�   6�   �� 5   
   -	   5        $��	dashboard��5   D   $ Vertical=�6   $	��Top=�6,   $
��left=�6U   $y�	Collapsed=�6p   .+�5      �� $ Vertical=�6   $��Center=�60   .+�5      �� $��.\images\logo.png��6   $	��200��63   $��30pt,30pt,0,20ptq�6?   $
��Left=�6Y   6v   �� 5      -
   5        $��	user_name��5      ���#fff$��20(�6;   $��SemiBold"�6I   $��30pt,0,10pt,0q�6_   �� 5      -   5        $��	last_sync��5      ���#fff$��Last Sync : Connecting..��6;   $��12(�6^   $��SemiBold"�6l   $��30pt,0,10pt,0q�6�   �� 5       $ 
Horizontal=�6   $
��Left=�66   .+�5!      �� $��.\images\folder.png��6   $��50��69   $��30pt,30pt,0,20ptq�6D   $
��Left=�6^   6{   �� 5"      -   5        $��folders_count��5"      $��10pt,40pt,0,0q�61   ���#fff$��	0 Folders��6Z   $��12(�6n   $��SemiBold"�6|   5#      �� 5$      $ 
Horizontal=�6   $
��Left=�66   .+�5%      �� $��.\images\file.png��6   $��40��67   $��33pt,0,0,20ptq�6B   $
��Left=�6Y   6v   �� 5&      -   5        $��files_count��5&      $��13pt,5pt,0,20ptq�6/   ���#fff$��0 Files��6Z   $��12(�6l   $��SemiBold"�6z   5'      �� 5(      $ 
Horizontal=�6   $
��Left=�66   .+�5)      �� $��.\images\drive.png��6   $��41��68   $��33pt,3pt,0,20ptq�6C   $
��Left=�6\   6z   �� 5*      -   5        $��	disc_size��5*      $��13pt,5pt,0,20ptq�6-   ���#fff$��0 KB Drive Size��6X   $��12(�6r   $��SemiBold"�6�   5+      5-      5/      50      51      